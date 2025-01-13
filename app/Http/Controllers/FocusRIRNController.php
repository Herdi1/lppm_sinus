<?php

namespace App\Http\Controllers;

use App\Models\FocusRIRN;
use Illuminate\Http\Request;

class FocusRIRNController extends Controller
{
    public function getFocusRIRN()
    {
        $data = FocusRIRN::all();
        return response()->json($data);
    }
}
