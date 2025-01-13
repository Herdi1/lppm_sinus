<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResearchMonev;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ResearchMonevController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create_research_monev_review,api'), only: ['store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('read_research_monev_review,api'), only: ['index', 'show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update_research_monev_review,api'), only: ['update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete_research_monev_review,api'), only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monev = ResearchMonev::all();
        return response()->json($monev);
    }
    public function show(ResearchMonev $researchMonev)
    {
        return response()->json($researchMonev);
    }
    public function getMonevReviewByResearchId($researchId)
    {
        $monev = ResearchMonev::where('research_id', $researchId)->get();

        return response()->json([
            'data' => $monev,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validate([
                'research_id' => 'required|exists:research,id',
                'comment1' => 'required|string',
                'comment2' => 'required|string',
                'comment3' => 'required|string',
                'comment4' => 'required|string',
                'comment5' => 'required|string',
                'comment6' => 'required|string',
                'score1' => 'required|integer',
                'score2' => 'required|integer',
                'score3' => 'required|integer',
                'score4' => 'required|integer',
                'score5' => 'required|integer',
                'reviewer_note' => 'required|string',
            ]);

            $monev = $request->user()->researchMonev()->create($data);

            DB::commit();
            return response()->json(['message' => 'Penilaian Monitoring dan Evaluasi berhasil disimpan.', 'data' => $monev], 200);
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
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {

            $monev = ResearchMonev::findOrFail($id);

            $data = $request->validate([
                'research_id' => 'sometimes|exists:research,id',
                'comment1' => 'sometimes|string',
                'comment2' => 'sometimes|string',
                'comment3' => 'sometimes|string',
                'comment4' => 'sometimes|string',
                'comment5' => 'sometimes|string',
                'comment6' => 'sometimes|string',
                'score1' => 'sometimes|integer',
                'score2' => 'sometimes|integer',
                'score3' => 'sometimes|integer',
                'score4' => 'sometimes|integer',
                'score5' => 'sometimes|integer',
                'reviewer_note' => 'sometimes|string',
            ]);

            $monev->update($data);

            DB::commit();
            return response()->json(['message' => 'Penilaian Monitoring dan Evaluasi berhasil disimpan.', 'data' => $monev], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $monev = ResearchMonev::findOrFail($id);
        $monev->delete();

        return response()->json([
            'message' => 'Penilaian Monitoring dan Evaluasi berhasil dihapus.'
        ], 200);
    }
}
