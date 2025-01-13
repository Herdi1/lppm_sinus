<?php

namespace App\Http\Controllers;

use App\Models\Research;
use Illuminate\Http\Request;
use App\Models\ResearchReview;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ResearchReviewController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create_research_review,api'), only: ['store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('read_research_review,api'), only: ['index', 'show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update_research_review,api'), only: ['update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete_research_review,api'), only: ['destroy']),
        ];
    }

    public function index()
    {
        $reviews = ResearchReview::all();
        return response()->json([
            'data' => $reviews
        ]);
    }
    public function show($id)
    {
        $researchReview = ResearchReview::find($id);

        if (!$researchReview) {
            return response()->json(['error' => 'Review penelitian tidak ditemukan.'], 404);
        }

        return response()->json(['data' => $researchReview]);
    }
    public function getReviewByResearchId($researchId)
    {
        $reviews = ResearchReview::where('research_id', $researchId)->with('reviewer')->get();

        return response()->json($reviews);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'research_id' => 'required|exists:research,id',
            'reviewer_id' => 'required|integer',
            'indicator_1' => 'required|boolean',
            'indicator_2' => 'required|boolean',
            'indicator_3' => 'required|boolean',
            'indicator_4' => 'required|boolean',
            'indicator_5' => 'required|boolean',
            'indicator_6' => 'required|boolean',
            'substance_score_1_1' => 'required|integer',
            'substance_score_1_2' => 'required|integer',
            'substance_score_1_3' => 'required|integer',
            'substance_score_2_1' => 'required|integer',
            'substance_score_2_2' => 'required|integer',
            'substance_score_2_3' => 'required|integer',
            'substance_score_3_1' => 'required|integer',
            'substance_score_3_2' => 'required|integer',
            'substance_score_3_3' => 'required|integer',
            'substance_score_4_1' => 'required|integer',
            'substance_score_4_2' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        $reviews = ResearchReview::create($data);

        if (ResearchReview::where('research_id', $data['research_id'])->count() === 2) {
            $research = Research::find($data['research_id']);
            if ($research) {
                $research->update(['status' => 5]);
            }
        }

        return response()->json([
            'message' => 'Penilaian penelitian berhasil disimpan.',
            'data' => $reviews
        ]);
    }

    public function update(Request $request, ResearchReview $researchReview)
    {
        $data = $request->validate([
            'research_id' => 'required|exists:research,id',
            'reviewer_id' => 'required|integer',
            'indicator_1' => 'required|boolean',
            'indicator_2' => 'required|boolean',
            'indicator_3' => 'required|boolean',
            'indicator_4' => 'required|boolean',
            'indicator_5' => 'required|boolean',
            'indicator_6' => 'required|boolean',
            'substance_score_1_1' => 'required|integer',
            'substance_score_1_2' => 'required|integer',
            'substance_score_1_3' => 'required|integer',
            'substance_score_2_1' => 'required|integer',
            'substance_score_2_2' => 'required|integer',
            'substance_score_2_3' => 'required|integer',
            'substance_score_3_1' => 'required|integer',
            'substance_score_3_2' => 'required|integer',
            'substance_score_3_3' => 'required|integer',
            'substance_score_4_1' => 'required|integer',
            'substance_score_4_2' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        $review = $researchReview->update($data);

        return response()->json([
            'message' => 'Penilaian penelitian telah diubah.',
            'data' => $review
        ]);
    }

    public function destroy(ResearchReview $researchReview)
    {
        $researchReview->delete();

        return ['message' => 'Penilaian penelitian telah dihapus.'];
    }
}
