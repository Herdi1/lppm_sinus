<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceLogbook;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ServiceLogbookController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create_community_service_logbook,api'), only: ['store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('read_community_service_logbook,api'), only: ['index', 'show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update_community_service_logbook,api'), only: ['update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete_community_service_logbook,api'), only: ['destroy']),
        ];
    }

    public function index()
    {
        $logbooks = ServiceLogbook::all();
        return response()->json($logbooks);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validate([
                'comunity_service_id' => 'required|exists:comunity_services,id',
                'date_activity' => 'required',
                'group_budget' => 'required',
                'nominal' => 'required|integer',
                'file_number' => 'required|integer',
                'activity_description' => 'required|string',
                'percentage' => 'required',
                'document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            if ($request->hasFile('document')) {
                $documentPath = $request->file('document')->store('logbook_document', 'public');
                $data['document'] = $documentPath;
            }

            $logbook = $request->user()->serviceLogbook()->create($data);

            DB::commit();
            return response()->json($logbook);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(ServiceLogbook $serviceLogbook)
    {
        return response()->json($serviceLogbook);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = $request->validate([
                'comunity_service_id' => 'sometimes|exists:comunity_services,id',
                'date_activity' => 'sometimes|date',
                'group_budget' => 'sometimes|string',
                'nominal' => 'sometimes|integer',
                'file_number' => 'sometimes|integer',
                'activity_description' => 'sometimes|string',
                'percentage' => 'sometimes|numeric',
                'document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);
            $serviceLogbook = ServiceLogbook::findOrfail($id);
            $serviceLogbook->update($data);

            if ($request->hasFile('document')) {
                if ($serviceLogbook->substance) {
                    Storage::disk('public')->delete($serviceLogbook->document);
                }

                $documentPath = $request->file('document')->store('document', 'public');
                $serviceLogbook->document = $documentPath;
                $serviceLogbook->save();
            }

            DB::commit();
            return response()->json(
                $serviceLogbook,
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(ServiceLogbook $serviceLogbook)
    {
        $serviceLogbook->delete();
        return ['message' => 'the report is already deleted'];
    }
}