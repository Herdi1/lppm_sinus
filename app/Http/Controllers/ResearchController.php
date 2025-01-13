<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Scope;
use App\Models\Status;
use App\Models\Research;
use Illuminate\Http\Request;
use App\Models\ResearchStatusChange;
use App\Http\Resources\ResearchResource;
use App\Http\Resources\SimpleResearchResource;
use App\Models\ActivityPeriod;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
// use setasign\Fpdi\Tcpdf\Fpdi;

class ResearchController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create_research,api'), only: ['store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('read_research,api'), only: ['index', 'show', 'getResearchByReviewer', 'researchProposal']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update_research,api'), only: ['update', 'updateStatus']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete_research,api'), only: ['delete']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update_status_research,api'), only: ['updateStatus']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('assign_reviewer,api'), only: ['addReviewer']), //operator
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('set_report_deadline,api'), only: ['setReportDeadline']), //operator
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('set_approval_funds,api'), only: ['setApprovalFunds']), //kepalaLPPm
        ];
    }

    public function index(Request $request)
    {
        $userId = $request->get('user_id');
        $prodiId = $request->get('prodi_id');
        $status = $request->get('status');
        $year = $request->get('year');
        $pageSize = $request->get('page_size', 10);
        $currentPage = $request->get('current_page', 1);

        if ($userId) {
            $createdByUser = Research::where('user_id', $userId);
            if ($year) {
                $createdByUser->where('year', $year);
            }
            if ($status) {
                $createdByUser->where('status', $status);
            }
            $createdResearch = $createdByUser
                ->with([
                    'scheme',
                    'user',
                    'scope',
                    'category',
                    'researchFocus',
                    'researchTheme',
                    'researchTopic',
                    'scienceCluster1',
                    'scienceCluster2',
                    'scienceCluster3',
                    'substances',
                    'progressReport',
                    'finalReport'
                ])
                ->get()
                ->map(function ($research) {
                    $research->role = 'ketua';
                    return $research;
                });

            $userAsMember = Research::whereHas('members', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            });
            if ($year) {
                $userAsMember->where('year', $year);
            }
            if ($status) {
                $userAsMember->where('status', $status);
            }
            $memberResearch = $userAsMember
                ->with([
                    'scheme',
                    'user',
                    'scope',
                    'category',
                    'researchFocus',
                    'researchTheme',
                    'researchTopic',
                    'scienceCluster1',
                    'scienceCluster2',
                    'scienceCluster3',
                    'substances',
                    'progressReport',
                    'finalReport'
                ])
                ->get()
                ->map(function ($research) {
                    $research->role = 'anggota';
                    return $research;
                });

            $allResearch = collect($createdResearch)->merge($memberResearch)->unique('id')->values();
        } else {
            $allResearch = Research::query()
                ->with([
                    'scheme',
                    'user',
                    'scope',
                    'category',
                    'researchFocus',
                    'researchTheme',
                    'researchTopic',
                    'scienceCluster1',
                    'scienceCluster2',
                    'scienceCluster3',
                    'substances',
                    'progressReport',
                    'finalReport'
                ])
                ->when($status, function ($query) use ($status) {
                    return $query->where('status', $status);
                })
                ->when($year, function ($query) use ($year) {
                    return $query->where('year', $year);
                })
                ->when($prodiId, function ($query) use ($prodiId) {
                    return $query->whereHas('user', function ($query) use ($prodiId) {
                        $query->where('id_prodi', $prodiId);
                    });
                })
                ->get();
        }

        $paginatedResearch = new LengthAwarePaginator(
            $allResearch->forPage($currentPage, $pageSize),
            $allResearch->count(),
            $pageSize,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return SimpleResearchResource::collection($paginatedResearch);
    }

    public function store(Request $request)
    {
        $activityPeriod = ActivityPeriod::where('type', 'research')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$activityPeriod) {
            return response()->json(['message' => "Periode pendaftaran telah ditutup."], 403);
        }

        DB::beginTransaction();

        try {
            $fields = $request->validate([
                'title' => 'required|max:255',
                'tkt_current' => 'required',
                'tkt_final' => 'required',
                'scheme_id' => 'required',
                'scope_id' => 'required',
                'category_id' => 'required',
                'focus_id' => 'required',
                'theme_id' => 'required',
                'topic_id' => 'required',
                'cluster_lv1' => 'required',
                'cluster_lv2' => 'required',
                'cluster_lv3' => 'required',
                'priority_id' => 'required',
                'year' => 'required',
                'duration' => 'required',
                'leader_name' => 'required|string',
                'leader_task' => 'required|string',
                'substance_id' => 'sometimes|integer',
                'substance' => 'nullable|file|mimes:pdf|max:2048',
                'status' => 'integer'
            ]);

            if ($request->hasFile('substance')) {
                $substanceDocumentPath = $request->file('substance')->store('substance_document', 'public');
                $fields['substance'] = $substanceDocumentPath;
            }

            $fields['period_id'] = $activityPeriod->id;

            $research = $request->user()->research()->create($fields);

            $outputs = $request->validate([
                'output' => 'array|sometimes',
                'output.*.year' => 'sometimes',
                'output.*.id_category_output' => 'sometimes',
                'output.*.id_type_output' => 'sometimes',
                'output.*.status' => 'sometimes',
                'output.*.description' => 'sometimes',
            ]);
            if (isset($request['output'])) {
                $research->output()->createMany($outputs['output']);
            }

            $budgetPlan = $request->validate([
                'budgetPlan' => 'array|sometimes',
                'budgetPlan.*.year' => 'sometimes',
                'budgetPlan.*.id_group_budget' => 'sometimes',
                'budgetPlan.*.id_component_budget' => 'sometimes',
                'budgetPlan.*.item' => 'nullable',
                'budgetPlan.*.unit' => 'sometimes',
                'budgetPlan.*.volume' => 'sometimes',
                'budgetPlan.*.price_unit' => 'sometimes',
                'budgetPlan.*.total' => 'sometimes',
            ]);
            if (isset($request['budgetPlan'])) {
                $research->budgetPlan()->createMany($budgetPlan['budgetPlan']);
            }

            $supportingDocument = $request->validate([
                'supportingDocument' => 'array',
                'supportingDocument.*.partner_name' => 'string',
                'supportingDocument.*.email' => 'string',
                'supportingDocument.*.institution' => 'string',
                'supportingDocument.*.country_code' => 'string',
                'supportingDocument.*.institution_address' => 'string',
                'supportingDocument.*.funding_contribution1' => 'integer',
                'supportingDocument.*.funding_contribution2' => 'integer',
                'supportingDocument.*.document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            if (isset($request['supportingDocument'])) {
                foreach ($supportingDocument['supportingDocument'] as $documentData) {
                    if (isset($documentData['document']) && $documentData['document'] instanceof UploadedFile) {
                        $documentPath = $documentData['document']->store('supporting_document', 'public');
                        $documentData['document'] = $documentPath;
                    }
                    $research->supportingDocument()->create($documentData);
                }
            }

            $members = $request->validate([
                'members' => 'array|sometimes',
                'members.*.id' => 'integer|exists:users,id',
                'members.*.task' => 'nullable|string|max:255',
                'members.*.research_role' => 'nullable|string|max:255',
                'members.*.status' => 'nullable',
            ]);
            if (isset($request['members'])) {
                foreach ($members['members'] as $member) {
                    $research->members()->attach($member['id'], [
                        'task' => $member['task'] ?? null,
                        'research_roles' => $member['research_role'] ?? 'dosen',
                        'status' => $member['status'] ?? 'pending'
                    ]);
                }
            }

            $students = $request->validate([
                'students' => 'array|sometimes',
                'students.*.name' => 'string|sometimes',
                'students.*.nim' => 'string|sometimes',
                'students.*.address' => 'string|sometimes',
                'students.*.email' => 'string|sometimes',
                'students.*.phone' => 'string|sometimes',
                'students.*.prodi' => 'string|sometimes',
                'students.*.role' => 'string|sometimes',
                'students.*.task' => 'sometimes|string|max:255',
            ]);
            if (isset($request['students'])) {
                foreach ($students['students'] as $students) {
                    $research->students()->create($students);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Data penelitian telah berhasil disimpan.',
                'data' => new ResearchResource($research),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Research $research)
    {
        $research->load(['output', 'budgetPlan', 'supportingDocument', 'members', 'students', 'reviewers', 'scheme', 'user', 'scope', 'category', 'researchFocus', 'researchTheme', 'researchTopic', 'scienceCluster1', 'scienceCluster2', 'scienceCluster3', 'substances']);
        return response()->json($research);
    }

    public function update(Request $request, Research $research)
    {
        DB::beginTransaction();

        try {
            $fields = $request->validate([
                'title' => 'sometimes|max:255',
                'tkt_current' => 'sometimes',
                'tkt_final' => 'sometimes',
                'scheme_id' => 'sometimes',
                'scope_id' => 'sometimes',
                'category_id' => 'sometimes',
                'focus_id' => 'sometimes',
                'theme_id' => 'sometimes',
                'topic_id' => 'sometimes',
                'cluster_lv1' => 'sometimes',
                'cluster_lv2' => 'sometimes',
                'cluster_lv3' => 'sometimes',
                'priority_id' => 'sometimes',
                'year' => 'sometimes',
                'duration' => 'sometimes',
                'members' => 'array|nullable',
                'members.*.id' => 'integer|exists:users,id',
                'members.*.task' => 'nullable|string|max:255',
                'members.*.status' => 'nullable',
                'members.*.research_role' => 'nullable|string|max:255',
                'students' => 'array|nullable',
                'students.*.name' => 'string|sometimes',
                'students.*.nim' => 'string|sometimes',
                'students.*.address' => 'string|sometimes',
                'students.*.email' => 'string|sometimes',
                'students.*.phone' => 'string|sometimes',
                'students.*.prodi' => 'string|sometimes',
                'students.*.role' => 'string|sometimes',
                'students.*.task' => 'sometimes|string|max:255',
                'substance_id' => 'sometimes|integer',
                'substance' => $request->hasFile('substance') ? 'nullable|file|mimes:pdf|max:2048' : 'nullable|string',
                'approved_funds' => 'sometimes|integer',
                'letter_of_intent' => $request->hasFile('letter_of_intent') ? 'nullable|file|mimes:pdf,doc,docx|max:2048' : 'nullable|string',
                'output' => 'array|nullable',
                'output.*.year' => 'sometimes',
                'output.*.id_category_output' => 'sometimes',
                'output.*.id_type_output' => 'sometimes',
                'output.*.status' => 'sometimes',
                'output.*.description' => 'sometimes',
                'budgetPlan' => 'array|nullable',
                'budgetPlan.*.year' => 'sometimes',
                'budgetPlan.*.id_group_budget' => 'sometimes',
                'budgetPlan.*.id_component_budget' => 'sometimes',
                'budgetPlan.*.item' => 'nullable',
                'budgetPlan.*.unit' => 'sometimes',
                'budgetPlan.*.volume' => 'sometimes',
                'budgetPlan.*.price_unit' => 'sometimes',
                'budgetPlan.*.total' => 'sometimes',
                'supportingDocument' => 'array|nullable',
                'supportingDocument.*.partner_name' => 'string',
                'supportingDocument.*.email' => 'string',
                'supportingDocument.*.institution' => 'string',
                'supportingDocument.*.country_code' => 'string',
                'supportingDocument.*.institution_address' => 'string',
                'supportingDocument.*.funding_contribution1' => 'integer',
                'supportingDocument.*.funding_contribution2' => 'integer',
                'supportingDocument.*.document' => $request->hasFile('supportingDocument.*.document') ? 'nullable|file|mimes:pdf,doc,docx|max:2048' : 'nullable|string',
                'status' => 'integer'
            ]);

            if ($request->hasFile('substance')) {
                if ($research->substance) {
                    Storage::disk('public')->delete($research->substance);
                }

                $substanceDocumentPath = $request->file('substance')->store('substance_document', 'public');
                $fields['substance'] = $substanceDocumentPath;
                // $research->save();
            } else {
                $fields['substance'] = $research->substance;
            }

            //surat kesanggupan
            if ($request->hasFile('letter_of_intent')) {
                if ($research->letter_of_intent) {
                    Storage::disk('public')->delete($research->letter_of_intent);
                }

                $filePath = $request->file('letter_of_intent')->store('letter_of_intent', 'public');
                $fields['letter_of_intent'] = $filePath;
                // $research->save();
            } else {
                $fields['letter_of_intent'] = $research->letter_of_intent;
            }

            $research->update($fields);

            if (isset($fields['output'])) {
                $research->output()->delete();
                foreach ($fields['output'] as $outputData) {
                    $research->output()->create($outputData);
                }
            }

            if (isset($fields['budgetPlan'])) {
                $research->budgetPlan()->delete();
                foreach ($fields['budgetPlan'] as $budgetData) {
                    $research->budgetPlan()->create($budgetData);
                }
            }

            if (isset($fields['supportingDocument'])) {
                $research->supportingDocument()->delete();
                foreach ($fields['supportingDocument'] as $index => $documentData) {
                    if (isset($documentData['document']) && $request->hasFile('supportingDocument.' . $index . '.document')) {
                        $filePath = $request->file('supportingDocument.' . $index . '.document')->store('supporting_document', 'public');

                        $documentData['document'] = $filePath;
                    }
                    $research->supportingDocument()->create($documentData);
                }
            }

            if (isset($fields['members'])) {
                $members = [];
                foreach ($fields['members'] as $member) {
                    $members[$member['id']] = [
                        'task' => $member['task'] ?? null,
                        'research_roles' => $member['research_role'] ?? 'dosen',
                        'status' => $member['status'] ?? 'pending'
                    ];
                }
                $research->members()->sync($members);
            }

            if (isset($fields['students'])) {
                $research->students()->delete();
                foreach ($fields['students'] as $student) {
                    $research->students()->create($student);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Data penelitian telah berhasil diubah',
                'data' => new ResearchResource($research->load('output', 'budgetPlan', 'supportingDocument', 'members', 'students')),
            ], 200);
            // return new ResearchResource($research);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage(), 'message' => 'Terjadi kesalahan ketika mengubah data.'], 500);
        }
    }

    public function destroy(Research $research)
    {
        if ($research->status != 1) {
            return response()->json(
                ['message' => 'Data penelitian tidak dapat dihapus.'],
                500
            );
        }
        $research->delete();

        return response()->json(['message' => 'Data penelitian telah dihapus'], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate([
            'newStatus' => 'required|integer|exists:statuses,id',
            'notes' => 'nullable|string',
        ]);

        $research = Research::findOrFail($id);

        $prevStatusId = $research->status ? $research->status : null;
        $research->status = $data['newStatus'];
        $research->save();
        $user = auth()->guard('api')->user();

        $statusChange = ResearchStatusChange::create([
            'research_id'   => $research->id,
            'prev_status_id' => $prevStatusId,
            'new_status_id' => $data['newStatus'],
            'notes'         => $data['notes'] ?? null,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Status penelitian telah diubah.',
            'statusChange' => $statusChange,
        ]);
    }


    public function addReviewers($id, Request $request)
    {
        $data = $request->validate([
            'reviewers' => 'required|array|size:2',
            'reviewers.*' => 'required|integer|exists:users,id',
        ]);

        $research = Research::findOrFail($id);

        $research->reviewers()->sync($data['reviewers']);

        return response()->json([
            'message' => 'Reviewer telah ditugaskan.',
            'research' => $research->load('reviewers')
        ]);
    }

    public function getResearchByUser($userId)
    {
        try {

            $createdByUser = Research::where('user_id', $userId)
                ->get()
                ->map(function ($research) {
                    $research->role = 'ketua';
                    return $research;
                });

            $userAsMember = Research::whereHas('members', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
                ->get()
                ->map(function ($research) {
                    $research->role = 'anggota';
                    return $research;
                });

            $allResearch = $createdByUser->merge($userAsMember)->unique('id');
            return response()->json($allResearch);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getResearchByReviewer($reviewerId)
    {
        $user = User::findOrFail($reviewerId);

        $researches = $user->reviewedResearches()->get();
        $researches->load(['user', 'scheme', 'user', 'scope', 'category', 'researchFocus', 'researchTheme', 'researchTopic', 'scienceCluster1', 'scienceCluster2', 'scienceCluster3', 'substances', 'reviews']);

        return response()->json([
            'data' => $researches,
        ]);
    }

    public function researchProposal($researchId)
    {
        try {

            $research = Research::findOrFail($researchId);
            $researchResource = new ResearchResource($research);
            $groupedBudgetPlans = $research['budgetPlan']
                ->groupBy('year')
                ->map(function ($plans, $year) {
                    return [
                        'year' => $year,
                        'plans' => $plans,
                        'total' => $plans->sum('total'),
                    ];
                });
            $leaderApproval = ResearchStatusChange::where('research_id', $research->id)->whereIn('new_status_id', [3, 6])->with(['user.roles'])->get();
            $pdf = Pdf::loadView('research_pdf', ['research' => $researchResource, 'budgetPlan' => $groupedBudgetPlans, 'approval' => $leaderApproval]);
            $generatedPdf = $pdf->output();

            if (!Storage::disk('public')->exists($research->substance)) {
                return response()->json(['error' => 'File not found'], 404);
            }

            $fileContent = Storage::disk('public')->get($research->substance);

            $fpdi = new Fpdi();

            $generatedPdfStream = fopen('php://memory', 'r+');
            fwrite($generatedPdfStream, $generatedPdf);
            rewind($generatedPdfStream);
            $generatedPdfPageCount = $fpdi->setSourceFile($generatedPdfStream);

            for ($page = 1; $page <= $generatedPdfPageCount; $page++) {
                $templateId = $fpdi->importPage($page);
                $fpdi->AddPage();
                $fpdi->useTemplate($templateId);
            }

            $uploadedPdfStream = fopen('php://memory', 'r+');
            fwrite($uploadedPdfStream, $fileContent);
            rewind($uploadedPdfStream);
            $uploadedPdfPageCount = $fpdi->setSourceFile($uploadedPdfStream);

            for ($page = 1; $page <= $uploadedPdfPageCount; $page++) {
                $templateId = $fpdi->importPage($page);
                $fpdi->AddPage();
                $fpdi->useTemplate($templateId);
            }

            return response($fpdi->Output('S'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Merged-Proposal.pdf"',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
        }
    }


    public function setReportDeadline(Request $request, $researchId)
    {
        $research = Research::findOrFail($researchId);

        $validatedData = $request->validate([
            'progress_report_deadline' => 'nullable|date|after_or_equal:today',
            'final_report_deadline' => 'nullable|date|after_or_equal:today',
        ]);

        $research->update([
            'progress_report_deadline' => $validatedData['progress_report_deadline'] ?? $research->progress_report_deadline,
            'final_report_deadline' => $validatedData['final_report_deadline'] ?? $research->final_report_deadline
        ]);

        return response()->json([
            'message' => 'Deadline laporan telah ditetapkan.',
            'data' => $research,
        ], 200);
    }

    public function setApprovalFunds(Request $request, $researchId)
    {
        $research = Research::findOrFail($researchId);

        $approvalFunds = $request->validate([
            'approval_funds' => 'required|integer'
        ]);

        $research->update(['approved_funds' => $approvalFunds['approval_funds']]);

        return response()->json([
            'message' => 'Dana disetujui telah diinput.'
        ], 200);
    }
}
