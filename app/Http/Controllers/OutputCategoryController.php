<?php

namespace App\Http\Controllers;

use App\Models\OutputCategory;
use Illuminate\Http\Request;

class OutputCategoryController extends Controller
{
    public function getOutputCategory($schemeId)
    {
        $data = OutputCategory::where('scheme_id', $schemeId)->get();
        return response()->json($data);
    }
}
