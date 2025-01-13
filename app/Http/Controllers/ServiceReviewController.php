<?php

namespace App\Http\Controllers;

use App\Models\ServiceReview;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class ServiceReviewController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create_community_service_review,api'), only:['store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('read_community_service_review,api'), only:['index','show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update_community_service_review,api'), only:['update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete_community_service_review,api'), only:['destroy']),
        ];
    }

    public function index()
    {
        $serviceReviews = ServiceReview::all();
        return response()->json([
            'data' => $serviceReviews
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'comunity_service_id' => 'required|exists:comunity_services,id',
            'reviewer_id' => 'required|integer',
            'indicator_1' => 'required|boolean',
            'indicator_2' => 'required|boolean',
            'indicator_3' => 'required|boolean',
            'indicator_4' => 'required|boolean',
            'indicator_5' => 'required|boolean',
            'indicator_6' => 'required|boolean',
            'indicator_7' => 'required|boolean',
            'indicator_8' => 'required|boolean',
            'indicator_9' => 'required|boolean',
            'indicator_10' => 'required|boolean',
            'indicator_11' => 'required|boolean',
            'indicator_12' => 'required|boolean',
            'substance_score_1' => 'required|integer',
            'substance_score_2' => 'required|integer',
            'substance_score_3' => 'required|integer',
            'substance_score_4' => 'required|integer',
            'substance_score_5' => 'required|integer',
            'substance_score_6' => 'required|integer',
            'substance_score_7' => 'required|integer',
            'substance_score_8' => 'required|integer',
            'substance_score_9' => 'required|integer',
            'substance_score_10' => 'required|integer',
            'substance_score_11' => 'required|integer',
            'substance_score_12' => 'required|integer',
            'substance_score_13' => 'required|integer',
            'substance_score_14' => 'required|integer',
            'substance_score_15' => 'required|integer',
            'substance_score_16' => 'required|integer',
            'substance_score_17' => 'required|integer',
            'substance_score_18' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        $serviceReviews = ServiceReview::create($data);

        return response()->json([
            'data' => $serviceReviews
        ]);
    }

    public function show($id)
    {
        $serviceReviews = ServiceReview::find($id);

        if (!$serviceReviews) {
            return response()->json(['error' => 'Community Service review not found'], 404);
        }

        return response()->json(['data' => $serviceReviews]);
    }

    public function update(Request $request, ServiceReview $serviceReview)
    {
        $data = $request->validate([
            'comunity_service_id' => 'required|exists:comunity_services,id',
            'reviewer_id' => 'required|integer',
            'indicator_1' => 'required|boolean',
            'indicator_2' => 'required|boolean',
            'indicator_3' => 'required|boolean',
            'indicator_4' => 'required|boolean',
            'indicator_5' => 'required|boolean',
            'indicator_6' => 'required|boolean',
            'indicator_7' => 'required|boolean',
            'indicator_8' => 'required|boolean',
            'indicator_9' => 'required|boolean',
            'indicator_10' => 'required|boolean',
            'indicator_11' => 'required|boolean',
            'indicator_12' => 'required|boolean',
            'substance_score_1' => 'required|integer',
            'substance_score_2' => 'required|integer',
            'substance_score_3' => 'required|integer',
            'substance_score_4' => 'required|integer',
            'substance_score_5' => 'required|integer',
            'substance_score_6' => 'required|integer',
            'substance_score_7' => 'required|integer',
            'substance_score_8' => 'required|integer',
            'substance_score_9' => 'required|integer',
            'substance_score_10' => 'required|integer',
            'substance_score_11' => 'required|integer',
            'substance_score_12' => 'required|integer',
            'substance_score_13' => 'required|integer',
            'substance_score_14' => 'required|integer',
            'substance_score_15' => 'required|integer',
            'substance_score_16' => 'required|integer',
            'substance_score_17' => 'required|integer',
            'substance_score_18' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        $review = $serviceReview->update($data);

        return response()->json([
            'data' => $review
        ]);
    }

    public function destroy(ServiceReview $serviceReviews)
    {
        $serviceReviews->delete();

        return ['message' => 'the post is already deleted'];
    }

    public function getReviewByServiceId($serviceId)
    {
        $serviceReviews = ServiceReview::where('comunity_service_id', $serviceId)->get();

        return response()->json([
            'data' => $serviceReviews,
        ]);
    }
}
