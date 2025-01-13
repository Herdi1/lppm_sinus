<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceMonev;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ServiceMonevController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create_community_service_monev_review,api'), only: ['store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('read_community_service_monev_review,api'), only: ['index', 'show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update_community_service_monev_review,api'), only: ['update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete_community_service_monev_review,api'), only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monev = ServiceMonev::all();
        return response()->json($monev);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validate([
                'comunity_service_id' => 'required|exists:comunity_services,id',
                'presence_1' => 'required|integer',
                'presence_2' => 'required|integer',
                'presence_3' => 'required|integer',
                'presence_4' => 'required|integer',
                'presence_5' => 'required|integer',
                'article_publication' => 'required|integer',
                'publication_journal' => 'required|integer',
                'recognition_sks_1' => 'required|integer',
                'recognition_sks_2' => 'required|integer',
                'video_1' => 'required|integer',
                'video_2' => 'required|integer',
                'video_3' => 'required|integer',
                'video_4' => 'required|integer',
                'video_5' => 'required|integer',
                'video_6' => 'required|integer',
                'video_7' => 'required|integer',
                'video_8' => 'required|integer',
                'poster_1' => 'required|integer',
                'poster_2' => 'required|integer',
                'poster_3' => 'required|integer',
                'budget_usage_1' => 'required|integer',
                'budget_usage_2' => 'required|integer',
                'budget_usage_3' => 'required|integer',
                'empowerment_1' => 'required|integer',
                'empowerment_2' => 'required|integer',
                'empowerment_3' => 'required|integer',
                'empowerment_4' => 'required|integer',
                'empowerment_5' => 'required|integer',
                'reviewer_note' => 'required|string',
            ]);

            $monev = $request->user()->serviceMonev()->create($data);

            DB::commit();
            return response()->json($monev);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceMonev $serviceMonev)
    {
        return response()->json($serviceMonev);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {

            $monev = ServiceMonev::findOrFail($id);

            $data = $request->validate([
                'comunity_service_id' => 'sometimes|exists:comunity_services,id',
                'presence_1' => 'sometimes|integer',
                'presence_2' => 'sometimes|integer',
                'presence_3' => 'sometimes|integer',
                'presence_4' => 'sometimes|integer',
                'presence_5' => 'sometimes|integer',
                'article_publication' => 'sometimes|integer',
                'publication_journal' => 'sometimes|integer',
                'recognition_sks_1' => 'sometimes|integer',
                'recognition_sks_2' => 'sometimes|integer',
                'video_1' => 'sometimes|integer',
                'video_2' => 'sometimes|integer',
                'video_3' => 'sometimes|integer',
                'video_4' => 'sometimes|integer',
                'video_5' => 'sometimes|integer',
                'video_6' => 'sometimes|integer',
                'video_7' => 'sometimes|integer',
                'video_8' => 'sometimes|integer',
                'poster_1' => 'sometimes|integer',
                'poster_2' => 'sometimes|integer',
                'poster_3' => 'sometimes|integer',
                'budget_usage_1' => 'sometimes|integer',
                'budget_usage_2' => 'sometimes|integer',
                'budget_usage_3' => 'sometimes|integer',
                'empowerment_1' => 'sometimes|integer',
                'empowerment_2' => 'sometimes|integer',
                'empowerment_3' => 'sometimes|integer',
                'empowerment_4' => 'sometimes|integer',
                'empowerment_5' => 'sometimes|integer',
                'reviewer_note' => 'sometimes|string',
            ]);

            $monev->update($data);

            DB::commit();
            return response()->json($monev);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $monev = ServiceMonev::findOrFail($id);
        $monev->delete();
    }

    public function getMonevReviewByServiceId($serviceId)
    {
        $monev = ServiceMonev::where('comunity_service_id', $serviceId)->get();

        return response()->json([
            'data' => $monev,
        ]);
    }
}
