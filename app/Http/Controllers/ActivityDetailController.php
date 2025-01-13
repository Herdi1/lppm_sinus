<?php

namespace App\Http\Controllers;

use App\Models\Research;
use Illuminate\Http\Request;
use App\Models\ActivityDetail;
use App\Models\ComunityService;
use App\Http\Resources\SimpleResearchResource;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controllers\HasMiddleware;

class ActivityDetailController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('add_activity,api'), only: ['addActivity']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update_activity,api'), only: ['updateActivityDetail']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('get_activity,api'), only: ['getDetail']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('get_all_activity,api'), only: ['getAllActivity']),
        ];
    }

    public function addActivity(Request $request)
    {
        $data = $request->validate([
            'activity' => 'required|string',
            'no_sk_lembaga' => 'required|string',
            'nama_lembaga' => 'required|string',
            'alamat_lembaga' => 'required|string',
            'no_telp' => 'required|string',
            'no_fax' => 'required|string',
            'email' => 'required|string',
            'website' => 'required|string',
            'id_kepala' => 'required|integer'
        ]);

        $activity = ActivityDetail::create($data);

        return
            response()->json([
                'message' => 'Activity detail created successfully.',
                'data' => $activity,
            ], 201);
    }

    public function updateActivityDetail(Request $request, ActivityDetail $activityDetail)
    {
        $data = $request->validate([
            'activity' => 'required|string',
            'no_sk_lembaga' => 'required|string',
            'nama_lembaga' => 'required|string',
            'alamat_lembaga' => 'required|string',
            'no_telp' => 'required|string',
            'no_fax' => 'required|string',
            'email' => 'required|string',
            'website' => 'required|string',
            'id_kepala' => 'required|integer'
        ]);

        // $activity = ActivityDetail::findOrFail($id);
        $activityDetail->update($data);

        return
            response()->json([
                'message' => 'Activity detail created successfully.',
                'data' => $activityDetail,
            ], 201);
    }

    public function getDetail($activityType)
    {
        $activity = ActivityDetail::where('activity', $activityType)->with('activity_leader')->first();

        if (!$activity) {
            return response()->json([
                'error' => 'Activity not found.'
            ], 404);
        }

        if ($activity->activity === 'research') {
            $dataCount = Research::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');
        } else {
            $dataCount = ComunityService::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');
        }

        return response()->json([
            'data' => $activity,
            'activity_by_status' => $dataCount
        ], 200);
    }

    public function getAllActivity(Request $request)
    {
        $pageSize = $request->get('page_size', 10);
        $currentPage = $request->get('current_page', 1);

        $researches = Research::with([
            'scheme',
            'user',
            'status'
        ])->get();
        $communityServices = ComunityService::with([
            'scheme',
            'user',
            // 'status'
        ])->get();

        $allActivity = collect($researches)->merge($communityServices)->values();

        $paginatedActivity = new LengthAwarePaginator(
            $allActivity->forPage($currentPage, $pageSize),
            $allActivity->count(),
            $pageSize,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return SimpleResearchResource::collection($paginatedActivity);
    }
}
