<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceFinalReport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ServiceFinalReportController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create_community_service_final_report,api'), only: ['store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('read_community_service_final_report,api'), only: ['index', 'show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update_community_service_final_report,api'), only: ['update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete_community_service_final_report,api'), only: ['destroy']),
        ];
    }

    public function index()
    {
        $finalReports = ServiceFinalReport::all();
        return response()->json($finalReports);
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
                'target_partners' => 'required',
                'productive_economic_society' => 'required',
                'nonproductive_economic_society' => 'required',
                'number_of_partners' => 'required',
                'partner_education' => 'required',
                'problem_areas' => 'required',
                'distance_partners' => 'required',
                'male_proposing_team' => 'required',
                'female_proposing_team' => 'required',
                'male_partners_team' => 'required',
                'female_partners_team' => 'required',
                'total_students' => 'required',
                'male_student' => 'required',
                'female_student' => 'required',
                'implementation_activities' => 'required',
                'implementation_time' => 'required',
                'program_sustainability' => 'required',
                'production_capacity_before_program' => 'required',
                'production_capacity_after_program' => 'required',
                'turnover_before_program' => 'required',
                'turnover_after_program' => 'required',
                'funding_sources' => 'required',
                'funding_amount' => 'required',
                'partner_role' => 'required',
                'partner_role_active' => 'required',
                'partner_role_passive' => 'required',
                'government_local_role' => 'required',
                'funding_contribution' => 'required',
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

            $finalReport = $request->user()->serviceFinalReport()->create($data);

            $output1 = $request->validate([
                'output_final_report1s' => 'array|required',
                'output_final_report1s.*.status' => 'required',
                'output_final_report1s.*.recognized_sks' => 'required',
                'output_final_report1s.*.recognized_courses' => 'required',
                'output_final_report1s.*.proof_recognition' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output1['output_final_report1s'] as $outputData1) {
                if (isset($outputData1['proof_recognition']) && $outputData1['proof_recognition'] instanceof UploadedFile) {
                    $proofRecognitionPath = $outputData1['proof_recognition']->store('output_proof_recognition', 'public');
                    $outputData1['proof_recognition'] = $proofRecognitionPath;
                }
                $finalReport->outputs1()->create($outputData1);
            }

            $output2 = $request->validate([
                'output_final_report2s' => 'array|required',
                'output_final_report2s.*.status' => 'required',
                'output_final_report2s.*.poster_documents' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output2['output_final_report2s'] as $outputData2) {
                if (isset($outputData2['poster_documents']) && $outputData2['poster_documents'] instanceof UploadedFile) {
                    $posterDocumentPath = $outputData2['poster_documents']->store('output_poster_documents', 'public');
                    $outputData2['poster_documents'] = $posterDocumentPath;
                }
                $finalReport->outputs2()->create($outputData2);
            }

            $output3 = $request->validate([
                'output_final_report3s' => 'array|required',
                'output_final_report3s.*.status' => 'required',
                'output_final_report3s.*.url_video' => 'required',
            ]);
            $finalReport->outputs3()->createMany($output3['output_final_report3s']);

            $output4 = $request->validate([
                'output_final_report4s' => 'array|required',
                'output_final_report4s.*.status_article' => 'required',
                'output_final_report4s.*.status_writer' => 'required',
                'output_final_report4s.*.journal_name' => 'required',
                'output_final_report4s.*.issn_eissn' => 'required',
                'output_final_report4s.*.indexing_agency' => 'required',
                'output_final_report4s.*.journal_url' => 'required',
                'output_final_report4s.*.title_article' => 'required',
                'output_final_report4s.*.manuscript_article' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'output_final_report4s.*.proof_submit' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output4['output_final_report4s'] as $outputData4) {
                if (isset($outputData4['manuscript_article']) && $outputData4['manuscript_article'] instanceof UploadedFile) {
                    $manuscriptPath = $outputData4['manuscript_article']->store('output_manuscript_article', 'public');
                    $outputData4['manuscript_article'] = $manuscriptPath;
                }
                if (isset($outputData4['proof_submit']) && $outputData4['proof_submit'] instanceof UploadedFile) {
                    $manuscriptPath = $outputData4['proof_submit']->store('output_proof_submit', 'public');
                    $outputData4['proof_submit'] = $manuscriptPath;
                }
                $finalReport->outputs4()->create($outputData4);
            }

            $output5 = $request->validate([
                'output_final_report5s' => 'array|required',
                'output_final_report5s.*.status' => 'required',
                'output_final_report5s.*.type_media' => 'required',
                'output_final_report5s.*.title' => 'required',
                'output_final_report5s.*.name' => 'required',
                'output_final_report5s.*.proof_support' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output5['output_final_report5s'] as $outputData5) {
                if (isset($outputData5['proof_support']) && $outputData5['proof_support'] instanceof UploadedFile) {
                    $proofSupportPath = $outputData5['proof_support']->store('output_proof_support', 'public');
                    $outputData5['proof_support'] = $proofSupportPath;
                }
                $finalReport->outputs5()->create($outputData5);
            }

            $output6 = $request->validate([
                'output_final_report6s' => 'array|required',
                'output_final_report6s.*.status' => 'required',
                'output_final_report6s.*.improvement_description' => 'required',
                'output_final_report6s.*.proof_improvement' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output6['output_final_report6s'] as $outputData6) {
                if (isset($outputData6['proof_improvement']) && $outputData6['proof_improvement'] instanceof UploadedFile) {
                    $proofImprovementPath = $outputData6['proof_improvement']->store('output_proof_improvement', 'public');
                    $outputData6['proof_improvement'] = $proofImprovementPath;
                }
                $finalReport->outputs6()->create($outputData6);
            }

            $output7 = $request->validate([
                'output_final_report7s' => 'array|required',
                'output_final_report7s.*.status' => 'required',
                'output_final_report7s.*.improvement_description' => 'required',
                'output_final_report7s.*.proof_improvement' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output7['output_final_report7s'] as $outputData7) {
                if (isset($outputData6['proof_improvement']) && $outputData7['proof_improvement'] instanceof UploadedFile) {
                    $proofImprovementPath = $outputData7['proof_improvement']->store('output_proof_improvement', 'public');
                    $outputData7['proof_improvement'] = $proofImprovementPath;
                }
                $finalReport->outputs7()->create($outputData7);
            }

            $output8 = $request->validate([
                'output_final_report8s' => 'array|required',
                'output_final_report8s.*.presentation' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output8['output_final_report8s'] as $outputData8) {
                if (isset($outputData8['presentation']) && $outputData8['presentation'] instanceof UploadedFile) {
                    $presentationPath = $outputData8['presentation']->store('output_presentation', 'public');
                    $outputData8['presentation'] = $presentationPath;
                }
                $finalReport->outputs8()->create($outputData8);
            }

            $output9 = $request->validate([
                'output_final_report9s' => 'array|required',
                'output_final_report9s.*.result_description' => 'required',
                'output_final_report9s.*.result_plans' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output9['output_final_report9s'] as $outputData9) {
                if (isset($outputData9['result_plans']) && $outputData9['result_plans'] instanceof UploadedFile) {
                    $resultPlanstPath = $outputData9['result_plans']->store('output_result_plans', 'public');
                    $outputData9['result_plans'] = $resultPlanstPath;
                }
                $finalReport->outputs9()->create($outputData9);
            }

            $output10 = $request->validate([
                'output_final_report10s' => 'array|required',
                'output_final_report10s.*.type' => 'required',
                'output_final_report10s.*.description' => 'required',
                'output_final_report10s.*.url' => 'required',
                'output_final_report10s.*.document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            foreach ($output10['output_final_report10s'] as $outputData10) {
                if (isset($outputData10['document']) && $outputData10['document'] instanceof UploadedFile) {
                    $documentPath = $outputData10['document']->store('output_document', 'public');
                    $outputData10['document'] = $documentPath;
                }
                $finalReport->outputs10()->create($outputData10);
            }

            DB::commit();
            return response()->json(
                [
                    $finalReport,
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

    public function show(ServiceFinalReport $serviceFinalReport)
    {
        return response()->json($serviceFinalReport);
    }

    public function update(Request $request, ServiceFinalReport $serviceFinalReport)
    {
        DB::beginTransaction();

        try {
            $data = $request->validate([
                'comunity_service_id' => 'required|exists:comunity_services,id',
                'summary' => 'sometimes|string',
                'keyword' => 'sometimes|max:255',
                'substance' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'partner_contribution' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'target_partners' => 'sometimes',
                'productive_economic_society' => 'sometimes',
                'nonproductive_economic_society' => 'sometimes',
                'number_of_partners' => 'sometimes',
                'partner_education' => 'sometimes',
                'problem_areas' => 'sometimes',
                'distance_partners' => 'sometimes',
                'male_proposing_team' => 'sometimes',
                'female_proposing_team' => 'sometimes',
                'male_partners_team' => 'sometimes',
                'female_partners_team' => 'sometimes',
                'total_students' => 'sometimes',
                'male_student' => 'sometimes',
                'female_student' => 'sometimes',
                'implementation_activities' => 'sometimes',
                'implementation_time' => 'sometimes',
                'program_sustainability' => 'sometimes',
                'production_capacity_before_program' => 'sometimes',
                'production_capacity_after_program' => 'sometimes',
                'turnover_before_program' => 'sometimes',
                'turnover_after_program' => 'sometimes',
                'funding_sources' => 'sometimes',
                'funding_amount' => 'sometimes',
                'partner_role' => 'sometimes',
                'partner_role_active' => 'sometimes',
                'partner_role_passive' => 'sometimes',
                'government_local_role' => 'sometimes',
                'funding_contribution' => 'sometimes',
                'budget_use' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceFinalReport->update($data);
            if ($request->hasFile('substance')) {
                if ($serviceFinalReport->substance) {
                    Storage::disk('public')->delete($serviceFinalReport->substance);
                }

                $substancePath = $request->file('substance')->store('substance', 'public');
                $serviceFinalReport->substance = $substancePath;
                $serviceFinalReport->save();
            }
            if ($request->hasFile('partner_contribution')) {
                if ($serviceFinalReport->partner_contribution) {
                    Storage::disk('public')->delete($serviceFinalReport->partner_contribution);
                }

                $partnerContributionPath = $request->file('partner_contribution')->store('partner_contribution', 'public');
                $serviceFinalReport->partner_contribution = $partnerContributionPath;
                $serviceFinalReport->save();
            }
            if ($request->hasFile('budget_use')) {
                if ($serviceFinalReport->budget_use) {
                    Storage::disk('public')->delete($serviceFinalReport->budget_use);
                }

                $budgetUsePath = $request->file('budget_use')->store('budget_use', 'public');
                $serviceFinalReport->budget_use = $budgetUsePath;
                $serviceFinalReport->save();
            }

            $output1 = $request->validate([
                'output_final_report1s' => 'array|required',
                'output_final_report1s.*.status' => 'sometimes',
                'output_final_report1s.*.recognized_sks' => 'sometimes',
                'output_final_report1s.*.recognized_courses' => 'sometimes',
                'output_final_report1s.*.proof_recognition' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceFinalReport->outputs1()->delete();
            foreach ($output1['output_final_report1s'] as $index => $outputData1) {
                if ($request->hasFile('output_final_report1s.' . $index . '.proof_recognition')) {
                    $proofRecognitionPath = $request->file('output_final_report1s.' . $index . '.proof_recognition')->store('output_proof_recognition', 'public');
                    $outputData1['proof_recognition'] = $proofRecognitionPath;
                } else {
                    $outputData1['proof_recognition'] = null;
                }
                $serviceFinalReport->outputs1()->create($outputData1);
            }

            $output2 = $request->validate([
                'output_final_report2s' => 'array|sometimes',
                'output_final_report2s.*.status' => 'sometimes',
                'output_final_report2s.*.poster_documents' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceFinalReport->outputs2()->delete();
            foreach ($output2['output_final_report2s'] as $index => $outputData2) {
                if ($request->hasFile('output_final_report2s.' . $index . '.poster_documents')) {
                    $posterDocumentsPath = $request->file('output_final_report2s.' . $index . '.poster_documents')->store('output_poster_documents', 'public');
                    $outputData2['poster_documents'] = $posterDocumentsPath;
                } else {
                    $outputData2['poster_documents'] = null;
                }
                $serviceFinalReport->outputs2()->create($outputData2);
            }

            $output3 = $request->validate([
                'output_final_report3s' => 'array|required',
                'output_final_report3s.*.status' => 'sometimes',
                'output_final_report3s.*.url_video' => 'sometimes',
            ]);
            $serviceFinalReport->outputs3()->delete();
            foreach ($output3['output_final_report3s'] as $index => $outputData3) {
                $serviceFinalReport->outputs3()->create($outputData3);
            }

            $output4 = $request->validate([
                'output_final_report4s' => 'array|required',
                'output_final_report4s.*.status_article' => 'sometimes',
                'output_final_report4s.*.status_writer' => 'sometimes',
                'output_final_report4s.*.journal_name' => 'sometimes',
                'output_final_report4s.*.issn_eissn' => 'sometimes',
                'output_final_report4s.*.indexing_agency' => 'sometimes',
                'output_final_report4s.*.journal_url' => 'sometimes',
                'output_final_report4s.*.title_article' => 'sometimes',
                'output_final_report4s.*.manuscript_article' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'output_final_report4s.*.proof_submit' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceFinalReport->outputs4()->delete();
            foreach ($output4['output_final_report4s'] as $index => $outputData4) {
                if ($request->hasFile("output_final_report4s.$index.manuscript_article")) {
                    $manuscriptPath = $request->file("output_final_report4s.$index.manuscript_article")
                        ->store('output_manuscript_article', 'public');
                    $outputData4['manuscript_article'] = $manuscriptPath;
                } else {
                    $outputData4['manuscript_article'] = null;
                }

                if ($request->hasFile("output_final_report4s.$index.proof_submit")) {
                    $proofSubmitPath = $request->file("output_final_report4s.$index.proof_submit")
                        ->store('output_proof_submit', 'public');
                    $outputData4['proof_submit'] = $proofSubmitPath;
                } else {
                    $outputData4['proof_submit'] = null;
                }
                $serviceFinalReport->outputs4()->create($outputData4);
            }

            $output5 = $request->validate([
                'output_final_report5s' => 'array|required',
                'output_final_report5s.*.status' => 'sometimes',
                'output_final_report5s.*.type_media' => 'sometimes',
                'output_final_report5s.*.title' => 'sometimes',
                'output_final_report5s.*.name' => 'sometimes',
                'output_final_report5s.*.proof_support' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceFinalReport->outputs5()->delete();
            foreach ($output5['output_final_report5s'] as $index => $outputData5) {
                if ($request->hasFile('output_final_report5s.' . $index . '.proof_support')) {
                    $proofSupportPath = $request->file('output_final_report5s.' . $index . '.proof_support')->store('output_proof_support', 'public');
                    $outputData5['proof_support'] = $proofSupportPath;
                } else {
                    $outputData5['proof_support'] = null;
                }
                $serviceFinalReport->outputs5()->create($outputData5);
            }

            $output6 = $request->validate([
                'output_final_report6s' => 'array|required',
                'output_final_report6s.*.status' => 'sometimes',
                'output_final_report6s.*.improvement_description' => 'sometimes',
                'output_final_report6s.*.proof_improvement' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceFinalReport->outputs6()->delete();
            foreach ($output6['output_final_report6s'] as $index => $outputData6) {
                if ($request->hasFile('output_final_report6s.' . $index . '.proof_improvement')) {
                    $proofImprovementPath = $request->file('output_final_report6s.' . $index . '.proof_improvement')->store('output_proof_improvement', 'public');
                    $outputData6['proof_improvement'] = $proofImprovementPath;
                } else {
                    $outputData6['proof_improvement'] = null;
                }
                $serviceFinalReport->outputs6()->create($outputData6);
            }

            $output7 = $request->validate([
                'output_final_report7s' => 'array|required',
                'output_final_report7s.*.status' => 'sometimes',
                'output_final_report7s.*.improvement_description' => 'sometimes',
                'output_final_report7s.*.proof_improvement' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceFinalReport->outputs7()->delete();
            foreach ($output7['output_final_report7s'] as $index => $outputData7) {
                if ($request->hasFile('output_final_report7s.' . $index . '.proof_improvement')) {
                    $proofImprovementPath = $request->file('output_final_report7s.' . $index . '.proof_improvement')->store('output_proof_improvement', 'public');
                    $outputData7['proof_improvement'] = $proofImprovementPath;
                } else {
                    $outputData7['proof_improvement'] = null;
                }
                $serviceFinalReport->outputs7()->create($outputData7);
            }

            $output8 = $request->validate([
                'output_final_report8s' => 'array|required',
                'output_final_report8s.*.presentation' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceFinalReport->outputs8()->delete();
            foreach ($output8['output_final_report8s'] as $index => $outputData8) {
                if ($request->hasFile('output_final_report8s.' . $index . '.presentation')) {
                    $presentationPath = $request->file('output_final_report8s.' . $index . '.presentation')->store('output_presentation', 'public');
                    $outputData8['presentation'] = $presentationPath;
                } else {
                    $outputData8['presentation'] = null;
                }
                $serviceFinalReport->outputs8()->create($outputData8);
            }

            $output9 = $request->validate([
                'output_final_report9s' => 'array|required',
                'output_final_report9s.*.result_description' => 'sometimes',
                'output_final_report9s.*.result_plans' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceFinalReport->outputs9()->delete();
            foreach ($output9['output_final_report9s'] as $index => $outputData9) {
                if ($request->hasFile('output_final_report9s.' . $index . '.result_plans')) {
                    $resultPlansPath = $request->file('output_final_report9s.' . $index . '.result_plans')->store('output_result_plans', 'public');
                    $outputData9['result_plans'] = $resultPlansPath;
                } else {
                    $outputData9['result_plans'] = null;
                }
                $serviceFinalReport->outputs9()->create($outputData9);
            }

            $output10 = $request->validate([
                'output_final_report10s' => 'array|required',
                'output_final_report10s.*.type' => 'sometimes',
                'output_final_report10s.*.description' => 'sometimes',
                'output_final_report10s.*.url' => 'sometimes',
                'output_final_report10s.*.document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceFinalReport->outputs10()->delete();
            foreach ($output10['output_final_report10s'] as $index => $outputData10) {
                if ($request->hasFile('output_final_report10s.' . $index . '.document')) {
                    $documentPath = $request->file('output_final_report10s.' . $index . '.document')->store('output_document', 'public');
                    $outputData10['document'] = $documentPath;
                } else {
                    $outputData10['document'] = null;
                }
                $serviceFinalReport->outputs10()->create($outputData10);
            }

            DB::commit();
            return response()->json([
                $serviceFinalReport,
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

    public function destroy(ServiceFinalReport $serviceFinalReport)
    {
        $serviceFinalReport->delete();
        return ['message' => 'the report is already deleted'];
    }
}
