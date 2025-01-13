<?php

namespace App\Http\Controllers;

use App\Models\ComunityService;
use App\Models\Logbook;
use App\Models\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class LogbookController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create_logbook,api'), only: ['store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('read_logbook,api'), only: ['index']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('update_logbook,api'), only: ['update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete_logbook,api'), only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        try {

            $userId = $request->get('user_id');
            $researchId = $request->get('id');
            $pageSize = $request->get('page_size');
            $currentPage = $request->get('current_page');

            $data = Logbook::query()
                ->when($userId, function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                })
                ->when($researchId, function ($query) use ($researchId) {
                    return $query->where('loggable_id', $researchId);
                })
                ->paginate($pageSize, ['*'], 'page', $currentPage);

            return response()->json($data);
            Log::info($data);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Token error',
                'error' => $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan ketika memuat Logbook.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {
            $data = $request->validate([
                'type' => 'required|string|in:research,comunity_services',
                'id' => 'required|string',
                'date_activity' => 'required|date',
                'activity_description' => 'required|string',
                'percentage' => 'required|string|min:0|max:100',
                'logbook_document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);

            if ($data['type'] === 'research') {
                $relatedModel = Research::findOrFail($data['id']);
            } else if ($data['type'] === 'comunity_services') {
                $relatedModel = ComunityService::findOrFail($data['id']);
            } else {
                return response()->json(['error' => 'Invalid type provided'], 400);
            }

            if ($request->hasFile('logbook_document')) {
                $logbookDocumentPath = $request->file('logbook_document')->store('logbook_document', 'public');
                $data['document'] = $logbookDocumentPath;
            }

            $data['user_id'] = $request->user()->id;

            unset($data['type'], $data['id']);

            $logbook = $relatedModel->logbooks()->create($data);

            DB::commit();
            return response()->json([
                'message' => 'Logbook telah disimpan.',
                'data' => $logbook
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($logbookId)
    {
        $logbook = Logbook::find($logbookId);

        if (!$logbook) {
            return response()->json(['error' => 'Logbook tidak dapat ditemukan'], 404);
        }

        return response()->json($logbook);
    }

    public function update(Request $request, $logbookId)
    {

        DB::beginTransaction();

        try {
            $data = $request->validate([
                'date_activity' => 'sometimes|date',
                'activity_description' => 'sometimes|string',
                'percentage' => 'sometimes|integer|min:0|max:100',
                'logbook_document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);

            $logbook = Logbook::findOrFail($logbookId);
            $logbook->update($data);

            if ($request->hasFile('logbook_document')) {
                if ($logbook->document) {
                    Storage::disk('public')->delete($logbook->document);
                }

                $filePath = $request->file('logbook_document')->store('log_document', 'public');
                $logbook->document = $filePath;
                $logbook->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Logbook telah diubah.',
                'data' => $logbook
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Logbook $logbook)
    {
        $logbook->delete();

        return ['message' => 'Logbook telah dihapus'];
    }
}
