<?php

namespace App\Http\Controllers;

use App\Models\OutputType;
use Illuminate\Http\Request;

class OutputTypeController extends Controller
{
    public function getOutputType()
    {
        $data = OutputType::all();
        return response()->json($data);
    }
}
