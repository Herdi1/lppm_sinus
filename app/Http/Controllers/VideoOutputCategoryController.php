<?php

namespace App\Http\Controllers;

use App\Models\VideoOutputCategory;
use Illuminate\Http\Request;

class VideoOutputCategoryController extends Controller
{
    public function getVideoOutputCategory()
    {
        $data = VideoOutputCategory::all();
        return response()->json($data);
    }
}
