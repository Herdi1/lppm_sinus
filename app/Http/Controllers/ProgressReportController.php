<?php

namespace App\Http\Controllers;

use App\Models\ComunityService;
use Illuminate\Http\Request;
use App\Models\ProgressReport;
use App\Models\Research;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;

class ProgressReportController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create_research_progress_report,api'), only: ['store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('read_research_progress_report,api'), only: ['index', 'show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update_research_progress_report,api'), only: ['update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete_research_progress_report,api'), only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $researchId = $request->get('research_id');
        $userId = $request->get('user_id');
        $reports = ProgressReport::where('research_id', $researchId)
            ->where('user_id', $userId)
            ->with(['user', 'research', 'outputs'])
            ->get();
        return response()->json($reports);
    }
    public function show($id)
    {
        $reports = ProgressReport::where('id', $id)->with(['user', 'research', 'outputs'])->get();
        return response()->json($reports);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validate([
                'research_id' => 'required|string',
                'summary' => 'required',
                'keyword' => 'required|max:255',
                'substance' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'partner_contribution' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'sptb' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'no_sk' => 'required',
                'no_contract' => 'required',
                'place_date' => 'required|string',
                'nip' => 'required|numeric',
                'description_1' => 'required|string',
                'realization_1' => 'required|integer',
                'description_2' => 'required|string',
                'realization_2' => 'required|integer',
                'description_3' => 'required|string',
                'realization_3' => 'required|integer',
                'description_4' => 'required|string',
                'realization_4' => 'required|integer',
                'description_5' => 'required|string',
                'realization_5' => 'required|integer',
                'description_6' => 'required|string',
                'realization_6' => 'required|integer',
                'status' => 'nullable',
            ]);

            if ($request->hasFile('substance')) {
                $substanceDocumentPath = $request->file('substance')->store('report_substance_document', 'public');
                $data['substance'] = $substanceDocumentPath;
            }
            if ($request->hasFile('partner_contribution')) {
                $partnerContributionPath = $request->file('partner_contribution')->store('report_partner_contribution', 'public');
                $data['partner_contribution'] = $partnerContributionPath;
            }
            if ($request->hasFile('sptb')) {
                $sptbPath = $request->file('sptb')->store('report_sptb_document', 'public');
                $data['sptb'] = $sptbPath;
            }

            $report = $request->user()->progressReport()->create($data);

            $output = $request->validate([
                'output_result' => 'array|required',
                'output_result.*.status_article' => 'required|in:submitted,accepted,published,draft',
                'output_result.*.status_writer' => 'required|in:first-author,co-author,author',
                'output_result.*.journal_name' => 'required',
                'output_result.*.issn' => 'required',
                'output_result.*.indexing_agency' => 'required',
                'output_result.*.journal_url' => 'required',
                'output_result.*.title_article' => 'required',
                'output_result.*.manuscript_article' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'output_result.*.proof_submit' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
            ]);
            foreach ($output['output_result'] as $outputData) {
                if (isset($outputData['manuscript_article']) && $outputData['manuscript_article'] instanceof UploadedFile) {
                    $manuscriptPath = $outputData['manuscript_article']->store('output_manuscript_article', 'public');
                    $outputData['manuscript_article'] = $manuscriptPath;
                }
                if (isset($outputData['proof_submit']) && $outputData['proof_submit'] instanceof UploadedFile) {
                    $manuscriptPath = $outputData['proof_submit']->store('output_proof_submit', 'public');
                    $outputData['proof_submit'] = $manuscriptPath;
                }
                $report->outputs()->create($outputData);
            }

            DB::commit();
            return response()->json(['message' => 'Laporan Kemajuan telah disimpan.', 'data' => $report], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProgressReport $progressReport)
    {
        DB::beginTransaction();

        try {
            $data = $request->validate([
                'research_id' => 'required|exists:research,id',
                'summary' => 'required',
                'keyword' => 'required|max:255',
                'substance' => $request->hasFile('substance') ? 'nullable|file|mimes:pdf,doc,docx|max:2048' : 'nullable|string',
                'partner_contribution' => $request->hasFile('partner_contribution') ? 'nullable|file|mimes:pdf,doc,docx|max:2048' : 'nullable|string',
                'sptb' => $request->hasFile('sptb') ? 'nullable|file|mimes:pdf,doc,docx|max:2048' : 'nullable|string',
                'no_sk' => 'required|numeric',
                'no_contract' => 'required|numeric',
                'place_date' => 'required|string',
                'nip' => 'required|numeric',
                'description_1' => 'required|string',
                'realization_1' => 'required|integer',
                'description_2' => 'required|string',
                'realization_2' => 'required|integer',
                'description_3' => 'required|string',
                'realization_3' => 'required|integer',
                'description_4' => 'required|string',
                'realization_4' => 'required|integer',
                'description_5' => 'required|string',
                'realization_5' => 'required|integer',
                'description_6' => 'required|string',
                'realization_6' => 'required|integer',
                'status' => 'nullable|string',
            ]);

            $progressReport->update($data);

            if ($request->hasFile('substance')) {
                if ($progressReport['substance']) {
                    Storage::disk('public')->delete($progressReport['substance']);
                }

                $filePath = $request->file('substance')->store('report_substance_document', 'public');
                $progressReport['substance'] = $filePath;
                $progressReport->save();
            }
            if ($request->hasFile('partner_contribution')) {
                if ($progressReport['partner_contribution']) {
                    Storage::disk('public')->delete($progressReport['partner_contribution']);
                }

                $filePath = $request->file('partner_contribution')->store('report_partner_contribution', 'public');
                $progressReport['partner_contribution'] = $filePath;
                $progressReport->save();
            }
            if ($request->hasFile('sptb')) {
                if ($progressReport['sptb']) {
                    Storage::disk('public')->delete($progressReport['sptb']);
                }

                $filePath = $request->file('sptb')->store('report_sptb_document', 'public');
                $progressReport['sptb'] = $filePath;
                $progressReport->save();
            }

            $output = $request->validate([
                'output_result' => 'array|required',
                'output_result.*.status_article' => 'required|in:submitted,accepted,published,draft',
                'output_result.*.status_writer' => 'required|in:first-author,co-author,author',
                'output_result.*.journal_name' => 'required',
                'output_result.*.issn' => 'required',
                'output_result.*.indexing_agency' => 'required',
                'output_result.*.journal_url' => 'required',
                'output_result.*.title_article' => 'required',
                'output_result.*.manuscript_article' => $request->hasFile('output_result.*.manuscript_article') ? 'nullable|file|mimes:pdf,doc,docx|max:2048' : 'nullable|string',
                'output_result.*.proof_submit' => $request->hasFile('output_result.*.proof_submit') ? 'nullable|file|mimes:pdf,doc,docx|max:2048' : 'nullable|string',
            ]);
            $progressReport->outputs()->delete();
            foreach ($output['output_result'] as $index => $outputData) {
                if (isset($outputData['manuscript_article']) && $request->hasFile('output_result.' . $index . 'manuscript_article')) {
                    $manuscriptPath = $request->file('output_result.' . $index . 'manuscript_article')->store('output_manuscript_article', 'public');
                    $outputData['manuscript_article'] = $manuscriptPath;
                }
                if (isset($outputData['proof_submit']) && $request->hasFile('output_result.' . $index . 'proof_submit')) {
                    $manuscriptPath = $request->file('output_result.' . $index . 'proof_submit')->store('output_proof_submit', 'public');
                    $outputData['proof_submit'] = $manuscriptPath;
                }
                $progressReport->outputs()->create($outputData);
            }

            DB::commit();
            return response()->json([
                'message' => 'Laporan Kemajuan telah diubah.',
                'data' => $progressReport
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgressReport $progressReport)
    {
        $progressReport->delete();

        return ['message' => 'Laporan kemajuan berhasil dihapus'];
    }
}
