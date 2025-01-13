<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Models\ActivityPeriod;
use App\Http\Resources\SimpleComunityServiceResource;
use App\Models\ComunityService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Resources\ComunityServiceResource;
use App\Models\ServiceStatusChange;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Fpdi as FpdiFpdi;


class ComunityServiceController extends Controller implements HasMiddleware
{
    // Middleware method...
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create_community_service,api'), only: ['store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('read_community_service,api'), only: ['index', 'show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update_community_service,api'), only: ['update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete_community_service,api'), only: ['delete']),
        ];
    }

    // Index method...
    public function index(Request $request)
    {
        $userId = $request->get('user_id');
        $status = $request->get('status');
        $year = $request->get('year');
        $pageSize = $request->get('page_size', 10);
        $currentPage = $request->get('current_page', 1);

        if ($userId) {
            $createdByUser = ComunityService::where('user_id', $userId);
            if ($year) {
                $createdByUser->where('year', $year);
            }
            if ($status) {
                $createdByUser->where('status', $status);
            }
            $createdComunityService = $createdByUser
                ->with([
                    'user',
                    'category',
                    'focus_thematic',
                    'focus_r_i_r_n_s',
                    'scheme', 'scope',
                    'scienceCluster1',
                    'scienceCluster2',
                    'scienceCluster3',
                    'outputPartner.category', 
                    'outputPartner.type', 
                    'budgetPlanService.group', 
                    'budgetPlanService.component', 
                    'partner.group', 
                    'partner.type', 
                    'supportingFile.type', 
                    'members'
                    ])
                ->get()
                ->map(function ($service) {
                    $service->role = 'ketua';
                    return $service;
                });


            $userAsMember = ComunityService::whereHas('members', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            });
            if ($year) {
                $userAsMember->where('year', $year);
            }
            if ($status) {
                $userAsMember->where('status', $status);
            }
            $memberComunityService = $userAsMember
                ->with([
                    'user',
                    'category',
                    'focus_thematic',
                    'focus_r_i_r_n_s',
                    'scheme',
                    'scope',
                    'clusterLv1',
                    'clusterLv2', 
                    'clusterLv3',
                    'outputPartner.category', 
                    'outputPartner.type', 
                    'budgetPlanService.group', 
                    'budgetPlanService.component', 
                    'partner.group', 
                    'partner.type', 
                    'supportingFile.type', 
                    'members'
                    ])
                ->get()
                ->map(function ($service) {
                    $service->role = 'anggota';
                    return $service;
                });

            $allService = collect($createdComunityService)->merge($memberComunityService)->unique('id')->values();
        } else {
            $allService = ComunityService::query()
                ->with([
                    'user',
                    'category',
                    'focus_thematic',
                    'focus_r_i_r_n_s',
                    'scheme',
                    'scope',
                    'scienceCluster1',
                    'scienceCluster2',
                    'scienceCluster3',
                    'outputPartner.category', 
                    'outputPartner.type', 
                    'budgetPlanService.group', 
                    'budgetPlanService.component', 
                    'partner.group', 
                    'partner.type', 
                    'supportingFile.type', 
                    'members',
                    ])
                ->when($status, function ($query) use ($status) {
                    return $query->where('status', $status);
                })
                ->when($year, function ($query) use ($year) {
                    return $query->where('year', $year);
                })
                ->get();
        }

        $paginatedComunityService = new LengthAwarePaginator(
            $allService->forPage($currentPage, $pageSize),
            $allService->count(),
            $pageSize,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // return ComunityServiceResource::collection($paginatedComunityService);
        return SimpleComunityServiceResource::collection($paginatedComunityService);
        // return response()->json($paginatedComunityService);
    }

    public function store(Request $request)
    {
        $activityPeriod = ActivityPeriod::where('type', 'community_service')
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->first();

    if (!$activityPeriod) {
        return response()->json(['error' => "No active community_service period."], 403);
    }
        DB::beginTransaction();
        try {
            $fields = $request->validate([
                'title' => 'required|max:255',
                'category_id' => 'required',
                'focus_thematic_id' => 'sometimes',
                'focus_rirn_id' => 'sometimes',
                'scheme_id' => 'required',
                'scope_id' => 'required',
                'year' => 'required',
                'duration' => 'required',
                'cluster_lv1' => 'required',
                'cluster_lv2' => 'required',
                'cluster_lv3' => 'required',
                'leader_name' => 'required',
                'leader_task' => 'required',
                'substance_document' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Biarkan null jika tidak ada file
                'status' => 'integer'
            ]);

            if ($request->hasFile('substance_document')) {
                $substanceDocumentPath = $request->file('substance_document')->store('substance_file', 'public');
                $fields['substance_document'] = $substanceDocumentPath;
            }

            $fields['period_id'] = $activityPeriod->id;
            // Simpan CommunityService
            $service = $request->user()->service()->create($fields);

            // Output Partners
            $outputPartners = $request->validate([
                'outputPartner' => 'array|required',
                'outputPartner.*.year' => 'nullable|integer',
                'outputPartner.*.id_category_output' => 'required',
                'outputPartner.*.id_type_output' => 'required',
                'outputPartner.*.status' => 'required|boolean',
                'outputPartner.*.description' => 'required',
            ]);
            $service->outputPartner()->createMany($outputPartners['outputPartner']);

            // Output Publications
            $outputPublications = $request->validate([
                'outputPublication' => 'array|required',
                'outputPublication.*.id_category_output' => 'required',
                'outputPublication.*.id_type_output' => 'required',
                'outputPublication.*.status' => 'required|boolean',
                'outputPublication.*.description' => 'required',
            ]);

            $service->outputPublication()->createMany($outputPublications['outputPublication']);

            // Output Media
            $outputMedia = $request->validate([
                'outputMedia' => 'array|required',
                'outputMedia.*.id_category_output' => 'required',
                'outputMedia.*.id_type_output' => 'required',
                'outputMedia.*.status' => 'required|boolean',
                'outputMedia.*.description' => 'required',
            ]);

            $service->outputMedia()->createMany($outputMedia['outputMedia']);

            // Output Video
            $outputVideo = $request->validate([
                'outputVideo' => 'array|required',
                'outputVideo.*.id_category_output' => 'required',
                'outputVideo.*.id_type_output' => 'required',
                'outputVideo.*.status' => 'required|boolean',
                'outputVideo.*.description' => 'required',
            ]);

            $service->outputVideo()->createMany($outputVideo['outputVideo']);

            // Rencana Anggaran
            $budgetPlanService = $request->validate([
                'budgetPlanService' => 'array|required',
                'budgetPlanService.*.year' => 'required',
                'budgetPlanService.*.id_group_budget' => 'required',
                'budgetPlanService.*.id_component_budget' => 'required',
                'budgetPlanService.*.item' => 'required',
                'budgetPlanService.*.unit' => 'required',
                'budgetPlanService.*.volume' => 'required',
                'budgetPlanService.*.price_unit' => 'required',
                'budgetPlanService.*.total' => 'required',
            ]);

            $service->budgetPlanService()->createMany($budgetPlanService['budgetPlanService']);

            // Validasi dan buat partners
            $partners = $request->validate([
                'partner' => 'array|required',
                'partner.*.name' => 'required',
                'partner.*.province' => 'required',
                'partner.*.leader_name' => 'required',
                'partner.*.group_id' => 'required',
                'partner.*.partner_type_id' => 'required',
                'partner.*.email' => 'required',
                'partner.*.funding_contribution' => 'required',
                'partner.*.document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);

            foreach ($partners['partner'] as &$partnerData) {
                if (isset($partnerData['document']) && $partnerData['document'] instanceof UploadedFile) {
                    $documentPath = $partnerData['document']->store('partner', 'public');
                    $partnerData['document'] = $documentPath;
                }
                $service->partner()->create($partnerData);
            }

            // Validasi dan buat file pendukung
            $supportingFiles = $request->validate([
                'supportingFile' => 'array|required',
                'supportingFile.*.type_id' => 'required',
                'supportingFile.*.document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);

            foreach ($supportingFiles['supportingFile'] as &$fileData) {
                if (isset($fileData['document']) && $fileData['document'] instanceof UploadedFile) {
                    $documentPath = $fileData['document']->store('supporting_file', 'public');
                    $fileData['document'] = $documentPath;
                }
                $service->supportingFile()->create($fileData);
            }

            // Melampirkan anggota dan siswa
            $members = $request->validate([
                'members' => 'array|required',
                'members.*.id' => 'integer|exists:users,id',
                'members.*.task' => 'required|string|max:255',
            ]);

            foreach ($members['members'] as $member) {
                $service->members()->attach($member['id'], ['task' => $member['task']]);
            }

            // $students = $request->validate([
            //     'students' => 'array|sometimes',
            //     'students.*.id' => 'integer|exists:students,id',
            //     'students.*.task' => 'sometimes|string|max:255',
            // ]);

            // foreach ($students['students'] as $student) {
            //     $service->students()->attach($student['id'], ['task' => $student['task']]);
            // }

            DB::commit();
            return new ComunityServiceResource($service);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ComunityService $service)
    {
        return new ComunityServiceResource($service);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ComunityService $comunityService)
    {
        DB::beginTransaction();

        try {
            $fields = $request->validate([
                'title' => 'sometimes|max:255',
                'category_id' => 'sometimes',
                'focus_thematic_id' => 'sometimes',
                'focus_rirn_id' => 'sometimes',
                'scheme_id' => 'sometimes',
                'scope_id' => 'sometimes',
                'year' => 'sometimes',
                'duration' => 'sometimes',
                'cluster_lv1' => 'sometimes',
                'cluster_lv2' => 'sometimes',
                'cluster_lv3' => 'sometimes',
                'leader_name' => 'sometimes',
                'leader_task' => 'sometimes',
                'members' => 'array|sometimes',
                'members.*.id' => 'integer|exists:users,id',
                'members.*.task' => 'sometimes|string|max:255',
                'students' => 'array|sometimes',
                'students.*.id' => 'integer|exists:students,id',
                'students.*.task' => 'sometimes|string|max:255',
                'substance_document' => 'nullable',
                'approved_funds' => 'sometimes|integer',
                'letter_of_intent' => 'sometimes|file|mimes:pdf,doc,docx|max:2048',
                'outputPartner' => 'array|sometimes',
                'outputPartner.*.year' => 'sometimes',
                'outputPartner.*.id_category_output' => 'sometimes',
                'outputPartner.*.id_type_output' => 'sometimes',
                'outputPartner.*.status' => 'sometimes',
                'outputPartner.*.description' => 'sometimes',
                'outputPublication' => 'array|sometimes',
                'outputPublication.*.id_category_output' => 'sometimes',
                'outputPublication.*.id_type_output' => 'sometimes',
                'outputPublication.*.status' => 'sometimes',
                'outputPublication.*.description' => 'sometimes',
                'outputMedia' => 'array|sometimes',
                'outputMedia.*.id_category_output' => 'sometimes',
                'outputMedia.*.id_type_output' => 'sometimes',
                'outputMedia.*.status' => 'sometimes',
                'outputMedia.*.description' => 'sometimes',
                'outputVideo' => 'array|sometimes',
                'outputVideo.*.id_category_output' => 'sometimes',
                'outputVideo.*.id_type_output' => 'sometimes',
                'outputVideo.*.status' => 'sometimes',
                'outputVideo.*.description' => 'sometimes',
                'budgetPlanService' => 'array|sometimes',
                'budgetPlanService.*.id_group_budget' => 'sometimes',
                'budgetPlanService.*.id_component_budget' => 'sometimes',
                'budgetPlanService.*.item' => 'nullable',
                'budgetPlanService.*.unit' => 'sometimes',
                'budgetPlanService.*.volume' => 'sometimes',
                'budgetPlanService.*.price_unit' => 'sometimes',
                'budgetPlanService.*.total' => 'sometimes',
                'partner' => 'array|sometimes',
                'partner.*.name' => 'sometimes',
                'partner.*.province' => 'sometimes',
                'partner.*.leader_name' => 'sometimes',
                'partner.*.group_id' => 'sometimes',
                'partner.*.partner_type_id' => 'sometimes',
                'partner.*.email' => 'sometimes',
                'partner.*.funding_contribution' => 'sometimes',
                'partner.*.document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'supportingFile' => 'array|sometimes',
                'supportingFile.*.type_id' => 'sometimes',
                'supportingFile.*.document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'status' => 'integer'
            ]);

            $comunityService->update($fields);

            if ($request->hasFile('substance_document')) {
                if ($comunityService->substance_document) {
                    Storage::disk('public')->delete($comunityService->substance_document);
                }

                $filePath = $request->file('substance_document')->store('substance_document', 'public');
                $comunityService->substance_document = $filePath;
                $comunityService->save();
            }

            // if ($request->hasFile('letter_of_intent')) {
            //     if ($service->letter_of_intent) {
            //         Storage::disk('public')->delete($service->letter_of_intent);
            //     }

            //     $filePath = $request->file('letter_of_intent_file')->store('letter_of_intent', 'public');
            //     $service->letter_of_intent = $filePath;
            //     $service->save();
            // }

            if ($request->hasFile('letter_of_intent')) {
                $letterOfIntentPath = $request->file('letter_of_intent')->store('letter_of_intent', 'public');
                $fields['letter_of_intent'] = $letterOfIntentPath;
            }

            if (isset($fields['outputPartner'])) {
                $comunityService->outputPartner()->delete();
                foreach ($fields['outputPartner'] as $outputPartnerUpdate) {
                    $comunityService->outputPartner()->create($outputPartnerUpdate);
                }
            }

            if (isset($fields['outputPublication'])) {
                $comunityService->outputPublication()->delete();
                foreach ($fields['outputPublication'] as $outputPublications) {
                    $comunityService->outputPublication()->create($outputPublications);
                }
            }

            if (isset($fields['outputMedia'])) {
                $comunityService->outputMedia()->delete();
                foreach ($fields['outputMedia'] as $outputMedia) {
                    $comunityService->outputMedia()->create($outputMedia);
                }
            }

            if (isset($fields['outputVideo'])) {
                $comunityService->outputVideo()->delete();
                foreach ($fields['outputVideo'] as $outputVideo) {
                    $comunityService->outputVideo()->create($outputVideo);
                }
            }

            if (isset($fields['budgetPlanService'])) {
                $comunityService->budgetPlanService()->delete();
                foreach ($fields['budgetPlanService'] as $budgetPlanService) {
                    $comunityService->budgetPlanService()->create($budgetPlanService);
                }
            }

            $comunityService->partner()->delete();
            foreach ($fields['partner'] as $index => $partnerDocument) {
                if (isset($partnerDocument['document']) && $request->hasFile('partner.' . $index . '.document')) {
                    $filePath = $request->file('partner.' . $index . '.document')->store('partner', 'public');
                    $partnerDocument['document'] = $filePath;
                }
                $comunityService->partner()->create($partnerDocument);
            }

            $comunityService->supportingFile()->delete();
            foreach ($fields['supportingFile'] as $index => $documentData) {
                if (isset($documentData['document']) && $request->hasFile('supporting_file.' . $index . '.document')) {
                    $filePath = $request->file('supporting_file.' . $index . '.document')->store('supporting_file', 'public');
                    $documentData['document'] = $filePath;
                }

                $comunityService->supportingFile()->create($documentData);
            }

            $members = [];
            foreach ($fields['members'] as $member) {
                $members[$member['id']] = ['task' => $member['task'] ?? null];
            }
            $comunityService->members()->sync($members);

            // $students = [];
            // foreach ($fields['students'] as $student) {
            //     $students[$student['id']] = ['task' => $student['task'] ?? null];
            // }
            // $comunityService->students()->sync($students);

            DB::commit();
            // Return the updated research with related data
            return new ComunityServiceResource($comunityService->load('outputPartner', 'outputPublication', 'outputMedia', 'outputVideo', 'budgetPlanService', 'partner', 'supportingFile', 'members', 'students'));
            // return new ResearchResource($comunityService);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ComunityService $service)
    {
        $service->delete();

        return ['message' => 'the post is already deleted'];
    }

    public function getComunityServiceByUser($userId)
    {
        $user = User::findOrFail($userId);

        $services = $user->comunityServices()->get();

        return response()->json([
            'data' => $services,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate([
            'new_status_id' => 'required|integer|exists:statuses,id',
            'notes' => 'required|string',
        ]);

        // Step 2: Find the service by its ID
        $service = ComunityService::findOrFail($id);

        // Step 3: Update the status
        $prevStatusId = $service->status ? $service->status : null;
        $service->status = $data['new_status_id'];
        $service->save();

        $statusChange = ServiceStatusChange::create([
            'comunity_service_id'   => $service->id,
            'prev_status_id' => $prevStatusId, // Previous status ID
            'new_status_id' => $data['new_status_id'], // New status ID
            'notes'         => $data['notes'] ?? null, // Notes for status change
        ]);

        // Step 4: Return the updated service
        return response()->json([
            'message' => 'Status updated successfully',
            'statusChange' => $statusChange,
        ]);
    }

    public function getComunityServiceByStatus($statusId)
    {
        $status = Status::findOrFail($statusId);

        $services = $status->service()->get();

        return response()->json([
            'data' => $services,
        ]);
    }

    public function addReviewers($id, Request $request)
    {
        $data = $request->validate([
            'reviewers' => 'required|array|size:2',
            'reviewers.*' => 'required|integer|exists:users,id',
        ]);

        $service = ComunityService::findOrFail($id);

        $service->reviewers()->sync($data['reviewers']);

        return response()->json([
            'message' => 'reviewers successfully assign',
            'comunity_service' => $service->load('reviewers')
        ]);
    }

    public function getComunityServiceByReviewer($reviewerId)
    {
        $user = User::findOrFail($reviewerId);

        $services = $user->reviewedServices()->get();

        return response()->json([
            'data' => $services,
        ]);
    }


    public function comunityServiceProposal($communityId)
    {
        $service = ComunityService::findOrFail($communityId);
        $comunityService = new ComunityServiceResource($service);
        $pdf = Pdf::loadView('community_service_pdf', ['communityService' => $comunityService]);
        $generatedPdf = $pdf->output();
        $fileName = 'community_service_proposal_' . $comunityService->id . '.pdf';
        $filePath = 'public/community_service_reports/' . $fileName;

        $filePath = 'public/' . $comunityService->substance_document;

        if (!Storage::exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $fileContent = Storage::get($filePath);

        $fpdi = new FpdiFpdi();

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
    }

    public function setReportDeadline(Request $request, $serviceId)
    {
        $service = ComunityService::findOrFail($serviceId);

        $validatedData = $request->validate([
            'progress_report_deadline' => 'nullable|date|after_or_equal:today',
            'final_report_deadline' => 'nullable|date|after_or_equal:today',
        ]);

        $service->update([
            'progress_report_deadline' => $validatedData['progress_report_deadline'] ?? $service->progress_report_deadline,
            'final_report_deadline' => $validatedData['final_report_deadline'] ?? $$service->final_report_deadline
        ]);

        return response()->json([
            'message' => 'Report deadline updated successfully.',
            'data' => $service,
        ]);
    }

}
