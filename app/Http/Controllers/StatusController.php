<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Research;
use Illuminate\Http\Request;
use App\Models\ResearchStatusChange;
use App\Http\Resources\ResearchResource;

class StatusController extends Controller
{
    public function getStatus()
    {
        $data = Status::all();
        return response()->json($data);
    }



    public function getStatusHistory($researchId)
    {
        $statusChanges = ResearchStatusChange::where('research_id', $researchId)->with('user.roles', 'prevStatus', 'newStatus')->get();

        return response()->json($statusChanges);
    }
}
