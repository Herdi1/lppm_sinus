<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Research;
use Illuminate\Http\Request;
use App\Mail\DeadlineReminder;
use App\Models\ActivityPeriod;
use Illuminate\Support\Facades\Mail;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ActivityPeriodController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create_activity_period,api'), only: ['store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('read_activity_period,api'), only: ['index', 'show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update_activity_period,api'), only: ['update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete_activity_period,api'), only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periods = ActivityPeriod::all();
        return response()->json($periods);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'type' => 'required|in:research,community_service'
        ]);

        $period = ActivityPeriod::create([
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'type' => $data['type'],
            'user' => auth()->guard('api')->user()->id
        ]);

        return
            response()->json([
                'message' => 'Activity period created successfully.',
                'data' => $period,
            ], 201);
    }

    public function show($id)
    {
        $activityPeriod = ActivityPeriod::findOrFail($id);
        return response()->json($activityPeriod);
    }

    public function update(Request $request, $id)
    {
        $activityPeriod = ActivityPeriod::findOrFail($id);
        $data = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'type' => 'required|in:research,community_service'
        ]);

        $activityPeriod->update([
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'type' => $data['type']
        ]);


        return response()->json(['message' => 'Research period updated successfully.', 'data' => $activityPeriod]);
    }

    public function destroy($id)
    {
        $activityPeriod = ActivityPeriod::findOrFail($id);
        $activityPeriod->delete();

        return ['message' => 'Research deleted successfully'];
    }

    public function deadlineReminder()
    {
        $now = Carbon::now();
        $weekLater = $now->addDays(7);

        $researches = Research::where(function ($query) use ($now, $weekLater) {
            $query->whereBetween('progress_report_deadline', [$now, $weekLater])->orWhereBetween('final_report_deadline', [$now, $weekLater]);
        })
            ->with('user')
            ->get();

        $researchesByUser = $researches->groupBy('user_id');

        foreach ($researchesByUser as $userId => $userResearch) {
            if (!$userResearch->first()->user) {
                return response("User not found for project ID: {$userResearch->first()->id}");
                // continue;
            }
            $user = $userResearch->first()->user;
            Mail::to($user->email)->send(new DeadlineReminder($user, $userResearch));
        }

        // $this->info('Reminder send successfully');
        return response()->json(['message' => 'set successfully', 'data' => $researches]);
    }
}
