<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Models\ResearchFinalReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ResearchFinalReportController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create_research_final_report,api'), only: ['store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('read_research_final_report,api'), only: ['index', 'show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update_research_final_report,api'), only: ['update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete_research_final_report,api'), only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $researchId = $request->get('research_id');
        $userId = $request->get('user_id');
        $reports = ResearchFinalReport::where('research_id', $researchId)
            ->where('user_id', $userId)
            ->with(['user', 'research', 'outputs'])
            ->get();;
        return response()->json($reports);
    }
    public function show(ResearchFinalReport $researchFinalReport)
    {
        $reports = $researchFinalReport->with(['user', 'research', 'outputs']);
        return response()->json($reports);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validate([
                'research_id' => 'required|exists:research,id',
                'summary' => 'required',
                'keyword' => 'required|max:255',
                'substance' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'partner_contribution' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'poster' => 'nullable|mimes:jpg,bmp,png,pdf,docx|max:1024',
                'video_profile' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
                'sptb' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
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
                'status' => 'nullable|integer',
            ]);

            if ($request->hasFile('substance')) {
                $substanceDocumentPath = $request->file('substance')
                    ->store('final_report_substance_document', 'public');
                $data['substance'] = $substanceDocumentPath;
            }
            if ($request->hasFile('partner_contribution')) {
                $partnerContributionPath = $request->file('final_partner_contribution')
                    ->store('report_partner_contribution', 'public');
                $data['partner_contribution'] = $partnerContributionPath;
            }
            if ($request->hasFile('sptb')) {
                $sptbPath = $request->file('sptb')->store('final_report_sptb_document', 'public');
                $data['sptb'] = $sptbPath;
            }
            if ($request->hasFile('poster')) {
                $finalReportPath = $request->file('poster')
                    ->store('final_report_poster', 'public');
                $data['poster'] = $finalReportPath;
            }
            if ($request->hasFile('video_profile')) {
                $finalReportPath = $request->file('video_profile')
                    ->store('final_report_video_profile', 'public');
                $data['video_profile'] = $finalReportPath;
            }

            $report = $request->user()->finalReport()->create($data);

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
                    $manuscriptPath = $outputData['manuscript_article']->store('output_final_manuscript_article', 'public');
                    $outputData['manuscript_article'] = $manuscriptPath;
                }
                if (isset($outputData['proof_submit']) && $outputData['proof_submit'] instanceof UploadedFile) {
                    $manuscriptPath = $outputData['proof_submit']->store('output_final_proof_submit', 'public');
                    $outputData['proof_submit'] = $manuscriptPath;
                }
                $report->outputs()->create($outputData);
            }

            DB::commit();
            return response()->json(['message' => 'Laporan Akhir berhasil disimpan.', 'data' => $report], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, ResearchFinalReport $researchFinalReport)
    {
        DB::beginTransaction();

        try {
            $data = $request->validate([
                'research_id' => 'required|exists:research,id',
                'summary' => 'required',
                'keyword' => 'required|max:255',
                'substance' =>
                $request->hasFile('substance') ? 'nullable|file|mimes:pdf,doc,docx|max:2048' : 'nullable|string',
                'partner_contribution' =>
                $request->hasFile('partner_contribution') ? 'nullable|file|mimes:pdf,doc,docx|max:2048' : 'nullable|string',
                'poster' =>
                $request->hasFile('poster') ? 'nullable|file|mimes:pdf,doc,docx|max:2048' : 'nullable|string',
                'video_profile' =>
                $request->hasFile('video_profile') ? 'nullable|file|mimes:pdf,doc,docx|max:2048' : 'nullable|string',
                'sptb' =>
                $request->hasFile('sptb') ? 'nullable|file|mimes:pdf,doc,docx|max:2048' : 'nullable|string',
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
                'status' => 'nullable|integer',
            ]);

            $researchFinalReport->update($data);

            if ($request->hasFile('substance')) {
                if ($researchFinalReport['substance']) {
                    Storage::disk('public')->delete($researchFinalReport['substance']);
                }

                $filePath = $request->file('substance')->store('final_report_substance_document', 'public');
                $researchFinalReport['substance'] = $filePath;
                $researchFinalReport->save();
            }
            if ($request->hasFile('partner_contribution')) {
                if ($researchFinalReport['partner_contribution']) {
                    Storage::disk('public')->delete($researchFinalReport['partner_contribution']);
                }

                $filePath = $request->file('partner_contribution')->store('final_report_partner_contribution', 'public');
                $researchFinalReport['partner_contribution'] = $filePath;
                $researchFinalReport->save();
            }
            if ($request->hasFile('sptb')) {
                if ($researchFinalReport['sptb']) {
                    Storage::disk('public')->delete($researchFinalReport['sptb']);
                }

                $filePath = $request->file('sptb')->store('final_report_sptb_document', 'public');
                $researchFinalReport['sptb'] = $filePath;
                $researchFinalReport->save();
            }
            if ($request->hasFile('poster')) {
                if ($researchFinalReport['poster']) {
                    Storage::disk('public')->delete($researchFinalReport['poster']);
                }
                $posterPath = $request->file('poster')
                    ->store('final_report_poster', 'public');
                $data['poster'] = $posterPath;
                $researchFinalReport->save();
            }
            if ($request->hasFile('video_profile')) {
                if ($researchFinalReport['video_profile']) {
                    Storage::disk('public')->delete($researchFinalReport['video_profile']);
                }
                $videoProfilePath = $request->file('video_profile')
                    ->store('final_report_video_profile', 'public');
                $data['video_profile'] = $videoProfilePath;
                $researchFinalReport->save();
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
                'output_result.*.manuscript_article' =>
                $request->hasFile('output_result.*.manuscript_article') ? 'nullable|file|mimes:pdf,doc,docx|max:2048' : 'nullable|string',
                'output_result.*.proof_submit' =>
                $request->hasFile('output_result.*.proof_submit') ? 'nullable|file|mimes:pdf,doc,docx|max:2048' : 'nullable|string',
            ]);
            $researchFinalReport->outputs()->delete();
            foreach ($output['output_result'] as $index => $outputData) {
                if (isset($outputData['manuscript_article']) && $request->hasFile('output_result.' . $index . 'manuscript_article')) {
                    $manuscriptPath = $request->file('output_result.' . $index . 'manuscript_article')->store('output_manuscript_article', 'public');
                    $outputData['manuscript_article'] = $manuscriptPath;
                }
                if (isset($outputData['proof_submit']) && $request->hasFile('output_result.' . $index . 'proof_submit')) {
                    $manuscriptPath = $request->file('output_result.' . $index . 'proof_submit')->store('output_proof_submit', 'public');
                    $outputData['proof_submit'] = $manuscriptPath;
                }
                $researchFinalReport->outputs()->create($outputData);
            }

            DB::commit();
            return response()->json([
                'message' => 'Laporan Akhir berhasil diubah.',
                'data' => $researchFinalReport
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResearchFinalReport $researchFinalReport)
    {
        $researchFinalReport->delete();

        return response()->json(['message' => 'Laporan akhir berhasil dihapus'], 200);
    }
}
