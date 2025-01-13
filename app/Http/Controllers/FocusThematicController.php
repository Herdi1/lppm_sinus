<?php

namespace App\Http\Controllers;

use App\Models\FocusThematic;
use Illuminate\Http\Request;

class FocusThematicController extends Controller
{
    public function getFocusThematic()
    {
        $data = FocusThematic::all();
        return response()->json($data);
    }
}
