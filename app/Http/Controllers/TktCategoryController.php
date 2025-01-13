<?php

namespace App\Http\Controllers;

use App\Models\TktCategory;
use Illuminate\Http\Request;

class TktCategoryController extends Controller
{
    public function getTktCategory()
    {
        $data = TktCategory::all();
        return response()->json($data);
    }
}
