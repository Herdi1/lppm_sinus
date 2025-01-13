<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceProgressReport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class ServiceProgressReportController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create_community_service_progress_report,api'), only: ['store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('read_community_service_progress_report,api'), only: ['index', 'show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update_community_service_progress_report,api'), only: ['update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete_community_service_progress_report,api'), only: ['destroy']),
        ];
    }

    public function index()
    {
        $reports = ServiceProgressReport::all();
        return response()->json($reports);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validate([
                'comunity_service_id' => 'required|exists:comunity_services,id',
                'summary' => 'required|string',
                'keyword' => 'required|max:255',
                'substance' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'partner_contribution' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'budget_use' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);

            if ($request->hasFile('substance')) {
                $substanceDocumentPath = $request->file('substance')->store('report_substance_document', 'public');
                $data['substance'] = $substanceDocumentPath;
            }
            if ($request->hasFile('partner_contribution')) {
                $partnerContributionPath = $request->file('partner_contribution')->store('report_partner_contribution', 'public');
                $data['partner_contribution'] = $partnerContributionPath;
            }
            if ($request->hasFile('budget_use')) {
                $budgetUsePath = $request->file('budget_use')->store('report_budget_use', 'public');
                $data['budget_use'] = $budgetUsePath;
            }

            $report = $request->user()->serviceProgressReport()->create($data);

            $output1 = $request->validate([
                'output_progress_report1s' => 'array|required',
                'output_progress_report1s.*.status' => 'required',
                'output_progress_report1s.*.recognized_sks' => 'required',
                'output_progress_report1s.*.recognized_courses' => 'required',
                'output_progress_report1s.*.proof_recognition' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output1['output_progress_report1s'] as $outputData1) {
                if (isset($outputData1['proof_recognition']) && $outputData1['proof_recognition'] instanceof UploadedFile) {
                    $proofRecognitionPath = $outputData1['proof_recognition']->store('output_proof_recognition', 'public');
                    $outputData1['proof_recognition'] = $proofRecognitionPath;
                }
                $report->outputs1()->create($outputData1);
            }

            $output2 = $request->validate([
                'output_progress_report2s' => 'array|required',
                'output_progress_report2s.*.status' => 'required',
                'output_progress_report2s.*.poster_documents' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output2['output_progress_report2s'] as $outputData2) {
                if (isset($outputData2['poster_documents']) && $outputData2['poster_documents'] instanceof UploadedFile) {
                    $posterDocumentPath = $outputData2['poster_documents']->store('output_poster_documents', 'public');
                    $outputData2['poster_documents'] = $posterDocumentPath;
                }
                $report->outputs2()->create($outputData2);
            }

            $output3 = $request->validate([
                'output_progress_report3s' => 'array|required',
                'output_progress_report3s.*.status' => 'required',
                'output_progress_report3s.*.url_video' => 'required',
            ]);
            $report->outputs3()->createMany($output3['output_progress_report3s']);

            $output4 = $request->validate([
                'output_progress_report4s' => 'array|required',
                'output_progress_report4s.*.status_article' => 'required',
                'output_progress_report4s.*.status_writer' => 'required',
                'output_progress_report4s.*.journal_name' => 'required',
                'output_progress_report4s.*.issn_eissn' => 'required',
                'output_progress_report4s.*.indexing_agency' => 'required',
                'output_progress_report4s.*.journal_url' => 'required',
                'output_progress_report4s.*.title_article' => 'required',
                'output_progress_report4s.*.manuscript_article' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'output_progress_report4s.*.proof_submit' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output4['output_progress_report4s'] as $outputData4) {
                if (isset($outputData4['manuscript_article']) && $outputData4['manuscript_article'] instanceof UploadedFile) {
                    $manuscriptPath = $outputData4['manuscript_article']->store('output_manuscript_article', 'public');
                    $outputData4['manuscript_article'] = $manuscriptPath;
                }
                if (isset($outputData4['proof_submit']) && $outputData4['proof_submit'] instanceof UploadedFile) {
                    $manuscriptPath = $outputData4['proof_submit']->store('output_proof_submit', 'public');
                    $outputData4['proof_submit'] = $manuscriptPath;
                }
                $report->outputs4()->create($outputData4);
            }

            $output5 = $request->validate([
                'output_progress_report5s' => 'array|required',
                'output_progress_report5s.*.status' => 'required',
                'output_progress_report5s.*.type_media' => 'required',
                'output_progress_report5s.*.title' => 'required',
                'output_progress_report5s.*.name' => 'required',
                'output_progress_report5s.*.proof_support' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output5['output_progress_report5s'] as $outputData5) {
                if (isset($outputData5['proof_support']) && $outputData5['proof_support'] instanceof UploadedFile) {
                    $proofSupportPath = $outputData5['proof_support']->store('output_proof_support', 'public');
                    $outputData5['proof_support'] = $proofSupportPath;
                }
                $report->outputs5()->create($outputData5);
            }

            $output6 = $request->validate([
                'output_progress_report6s' => 'array|required',
                'output_progress_report6s.*.status' => 'required',
                'output_progress_report6s.*.improvement_description' => 'required',
                'output_progress_report6s.*.proof_improvement' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output6['output_progress_report6s'] as $outputData6) {
                if (isset($outputData6['proof_improvement']) && $outputData6['proof_improvement'] instanceof UploadedFile) {
                    $proofImprovementPath = $outputData6['proof_improvement']->store('output_proof_improvement', 'public');
                    $outputData6['proof_improvement'] = $proofImprovementPath;
                }
                $report->outputs6()->create($outputData6);
            }

            $output7 = $request->validate([
                'output_progress_report7s' => 'array|required',
                'output_progress_report7s.*.status' => 'required',
                'output_progress_report7s.*.improvement_description' => 'required',
                'output_progress_report7s.*.proof_improvement' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output7['output_progress_report7s'] as $outputData7) {
                if (isset($outputData6['proof_improvement']) && $outputData7['proof_improvement'] instanceof UploadedFile) {
                    $proofImprovementPath = $outputData7['proof_improvement']->store('output_proof_improvement', 'public');
                    $outputData7['proof_improvement'] = $proofImprovementPath;
                }
                $report->outputs7()->create($outputData7);
            }

            $output8 = $request->validate([
                'output_progress_report8s' => 'array|required',
                'output_progress_report8s.*.presentation' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output8['output_progress_report8s'] as $outputData8) {
                if (isset($outputData8['presentation']) && $outputData8['presentation'] instanceof UploadedFile) {
                    $presentationPath = $outputData8['presentation']->store('output_presentation', 'public');
                    $outputData8['presentation'] = $presentationPath;
                }
                $report->outputs8()->create($outputData8);
            }

            $output9 = $request->validate([
                'output_progress_report9s' => 'array|required',
                'output_progress_report9s.*.result_description' => 'required',
                'output_progress_report9s.*.result_plans' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output9['output_progress_report9s'] as $outputData9) {
                if (isset($outputData9['result_plans']) && $outputData9['result_plans'] instanceof UploadedFile) {
                    $resultPlanstPath = $outputData9['result_plans']->store('output_result_plans', 'public');
                    $outputData9['result_plans'] = $resultPlanstPath;
                }
                $report->outputs9()->create($outputData9);
            }

            $output10 = $request->validate([
                'output_progress_report10s' => 'array|required',
                'output_progress_report10s.*.type' => 'required',
                'output_progress_report10s.*.description' => 'required',
                'output_progress_report10s.*.url' => 'required',
                'output_progress_report10s.*.document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output10['output_progress_report10s'] as $outputData10) {
                if (isset($outputData10['document']) && $outputData10['document'] instanceof UploadedFile) {
                    $documentPath = $outputData10['document']->store('output_document', 'public');
                    $outputData10['document'] = $documentPath;
                }
                $report->outputs10()->create($outputData10);
            }

            DB::commit();
            return response()->json(
                [
                    $report,
                    $outputData1,
                    $outputData2,
                    $output3,
                    $outputData4,
                    $outputData5,
                    $outputData6,
                    $outputData7,
                    $outputData8,
                    $outputData9,
                    $outputData10
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(ServiceProgressReport $serviceProgressReport)
    {
        return response()->json($serviceProgressReport);
    }

    public function update(Request $request, ServiceProgressReport $serviceProgressReport)
    {
        DB::beginTransaction();

        try {
            $data = $request->validate([
                'comunity_service_id' => 'required|exists:comunity_services,id',
                'summary' => 'sometimes|string',
                'keyword' => 'sometimes|max:255',
                'substance' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'partner_contribution' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'budget_use' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceProgressReport->update($data);
            if ($request->hasFile('substance')) {
                if ($serviceProgressReport->substance) {
                    Storage::disk('public')->delete($serviceProgressReport->substance);
                }

                $substancePath = $request->file('substance')->store('substance', 'public');
                $serviceProgressReport->substance = $substancePath;
                $serviceProgressReport->save();
            }
            if ($request->hasFile('partner_contribution')) {
                if ($serviceProgressReport->partner_contribution) {
                    Storage::disk('public')->delete($serviceProgressReport->partner_contribution);
                }

                $partnerContributionPath = $request->file('partner_contribution')->store('partner_contribution', 'public');
                $serviceProgressReport->partner_contribution = $partnerContributionPath;
                $serviceProgressReport->save();
            }
            if ($request->hasFile('budget_use')) {
                if ($serviceProgressReport->budget_use) {
                    Storage::disk('public')->delete($serviceProgressReport->budget_use);
                }

                $budgetUsePath = $request->file('budget_use')->store('budget_use', 'public');
                $serviceProgressReport->budget_use = $budgetUsePath;
                $serviceProgressReport->save();
            }

            $output1 = $request->validate([
                'output_progress_report1s' => 'array|required',
                'output_progress_report1s.*.status' => 'sometimes',
                'output_progress_report1s.*.recognized_sks' => 'sometimes',
                'output_progress_report1s.*.recognized_courses' => 'sometimes',
                'output_progress_report1s.*.proof_recognition' => 'sometimes|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceProgressReport->outputs1()->delete();
            foreach ($output1['output_progress_report1s'] as $index => $outputData1) {
                if ($request->hasFile('output_progress_report1s.' . $index . '.proof_recognition')) {
                    $proofRecognitionPath = $request->file('output_progress_report1s.' . $index . '.proof_recognition')->store('output_proof_recognition', 'public');
                    $outputData1['proof_recognition'] = $proofRecognitionPath;
                } else {
                    $outputData1['proof_recognition'] = null;
                }
                $serviceProgressReport->outputs1()->create($outputData1);
            }

            $output2 = $request->validate([
                'output_progress_report2s' => 'array|sometimes',
                'output_progress_report2s.*.status' => 'sometimes',
                'output_progress_report2s.*.poster_documents' => 'sometimes|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceProgressReport->outputs2()->delete();
            foreach ($output2['output_progress_report2s'] as $index => $outputData2) {
                if ($request->hasFile('output_progress_report2s.' . $index . '.poster_documents')) {
                    $posterDocumentsPath = $request->file('output_progress_report2s.' . $index . '.poster_documents')->store('output_poster_documents', 'public');
                    $outputData2['poster_documents'] = $posterDocumentsPath;
                } else {
                    $outputData2['poster_documents'] = null;
                }
                $serviceProgressReport->outputs2()->create($outputData2);
            }

            $output3 = $request->validate([
                'output_progress_report3s' => 'array|required',
                'output_progress_report3s.*.status' => 'sometimes',
                'output_progress_report3s.*.url_video' => 'sometimes',
            ]);
            $serviceProgressReport->outputs3()->delete();
            foreach ($output3['output_progress_report3s'] as $index => $outputData3) {
                $serviceProgressReport->outputs3()->create($outputData3);
            }

            $output4 = $request->validate([
                'output_progress_report4s' => 'array|required',
                'output_progress_report4s.*.status_article' => 'sometimes',
                'output_progress_report4s.*.status_writer' => 'sometimes',
                'output_progress_report4s.*.journal_name' => 'sometimes',
                'output_progress_report4s.*.issn_eissn' => 'sometimes',
                'output_progress_report4s.*.indexing_agency' => 'sometimes',
                'output_progress_report4s.*.journal_url' => 'sometimes',
                'output_progress_report4s.*.title_article' => 'sometimes',
                'output_progress_report4s.*.manuscript_article' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'output_progress_report4s.*.proof_submit' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceProgressReport->outputs4()->delete();
            foreach ($output4['output_progress_report4s'] as $index => $outputData4) {
                if ($request->hasFile("output_progress_report4s.$index.manuscript_article")) {
                    $manuscriptPath = $request->file("output_progress_report4s.$index.manuscript_article")
                        ->store('output_manuscript_article', 'public');
                    $outputData4['manuscript_article'] = $manuscriptPath;
                } else {
                    $outputData4['manuscript_article'] = null;
                }

                if ($request->hasFile("output_progress_report4s.$index.proof_submit")) {
                    $proofSubmitPath = $request->file("output_progress_report4s.$index.proof_submit")
                        ->store('output_proof_submit', 'public');
                    $outputData4['proof_submit'] = $proofSubmitPath;
                } else {
                    $outputData4['proof_submit'] = null;
                }
                $serviceProgressReport->outputs4()->create($outputData4);
            }

            $output5 = $request->validate([
                'output_progress_report5s' => 'array|required',
                'output_progress_report5s.*.status' => 'sometimes',
                'output_progress_report5s.*.type_media' => 'sometimes',
                'output_progress_report5s.*.title' => 'sometimes',
                'output_progress_report5s.*.name' => 'sometimes',
                'output_progress_report5s.*.proof_support' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceProgressReport->outputs5()->delete();
            foreach ($output5['output_progress_report5s'] as $index => $outputData5) {
                if ($request->hasFile('output_progress_report5s.' . $index . '.proof_support')) {
                    $proofSupportPath = $request->file('output_progress_report5s.' . $index . '.proof_support')->store('output_proof_support', 'public');
                    $outputData5['proof_support'] = $proofSupportPath;
                } else {
                    $outputData5['proof_support'] = null;
                }
                $serviceProgressReport->outputs5()->create($outputData5);
            }

            $output6 = $request->validate([
                'output_progress_report6s' => 'array|required',
                'output_progress_report6s.*.status' => 'sometimes',
                'output_progress_report6s.*.improvement_description' => 'sometimes',
                'output_progress_report6s.*.proof_improvement' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceProgressReport->outputs6()->delete();
            foreach ($output6['output_progress_report6s'] as $index => $outputData6) {
                if ($request->hasFile('output_progress_report6s.' . $index . '.proof_improvement')) {
                    $proofImprovementPath = $request->file('output_progress_report6s.' . $index . '.proof_improvement')->store('output_proof_improvement', 'public');
                    $outputData6['proof_improvement'] = $proofImprovementPath;
                } else {
                    $outputData6['proof_improvement'] = null;
                }
                $serviceProgressReport->outputs6()->create($outputData6);
            }

            $output7 = $request->validate([
                'output_progress_report7s' => 'array|required',
                'output_progress_report7s.*.status' => 'sometimes',
                'output_progress_report7s.*.improvement_description' => 'sometimes',
                'output_progress_report7s.*.proof_improvement' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceProgressReport->outputs7()->delete();
            foreach ($output7['output_progress_report7s'] as $index => $outputData7) {
                if ($request->hasFile('output_progress_report7s.' . $index . '.proof_improvement')) {
                    $proofImprovementPath = $request->file('output_progress_report7s.' . $index . '.proof_improvement')->store('output_proof_improvement', 'public');
                    $outputData7['proof_improvement'] = $proofImprovementPath;
                } else {
                    $outputData7['proof_improvement'] = null;
                }
                $serviceProgressReport->outputs7()->create($outputData7);
            }

            $output8 = $request->validate([
                'output_progress_report8s' => 'array|required',
                'output_progress_report8s.*.presentation' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceProgressReport->outputs8()->delete();
            foreach ($output8['output_progress_report8s'] as $index => $outputData8) {
                if ($request->hasFile('output_progress_report8s.' . $index . '.presentation')) {
                    $presentationPath = $request->file('output_progress_report8s.' . $index . '.presentation')->store('output_presentation', 'public');
                    $outputData8['presentation'] = $presentationPath;
                } else {
                    $outputData8['presentation'] = null;
                }
                $serviceProgressReport->outputs8()->create($outputData8);
            }

            $output9 = $request->validate([
                'output_progress_report9s' => 'array|required',
                'output_progress_report9s.*.result_description' => 'sometimes',
                'output_progress_report9s.*.result_plans' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceProgressReport->outputs9()->delete();
            foreach ($output9['output_progress_report9s'] as $index => $outputData9) {
                if ($request->hasFile('output_progress_report9s.' . $index . '.result_plans')) {
                    $resultPlansPath = $request->file('output_progress_report9s.' . $index . '.result_plans')->store('output_result_plans', 'public');
                    $outputData9['result_plans'] = $resultPlansPath;
                } else {
                    $outputData9['result_plans'] = null;
                }
                $serviceProgressReport->outputs9()->create($outputData9);
            }

            $output10 = $request->validate([
                'output_progress_report10s' => 'array|required',
                'output_progress_report10s.*.type' => 'sometimes',
                'output_progress_report10s.*.description' => 'sometimes',
                'output_progress_report10s.*.url' => 'sometimes',
                'output_progress_report10s.*.document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceProgressReport->outputs10()->delete();
            foreach ($output10['output_progress_report10s'] as $index => $outputData10) {
                if ($request->hasFile('output_progress_report10s.' . $index . '.document')) {
                    $documentPath = $request->file('output_progress_report10s.' . $index . '.document')->store('output_document', 'public');
                    $outputData10['document'] = $documentPath;
                } else {
                    $outputData10['document'] = null;
                }
                $serviceProgressReport->outputs10()->create($outputData10);
            }

            DB::commit();
            return response()->json([
                $serviceProgressReport,
                $outputData1,
                $outputData2,
                $output3,
                $outputData4,
                $outputData5,
                $outputData6,
                $outputData7,
                $outputData8,
                $outputData9,
                $outputData10
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(ServiceProgressReport $serviceProgressReport)
    {
        $serviceProgressReport->delete();
        return ['message' => 'the report is already deleted'];
    }
}
