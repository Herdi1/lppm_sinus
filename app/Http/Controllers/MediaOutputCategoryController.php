<?php

namespace App\Http\Controllers;

use App\Models\MediaOutputCategory;
use Illuminate\Http\Request;

class MediaOutputCategoryController extends Controller
{
    public function getMediaOutputCategory()
    {
        $data = MediaOutputCategory::all();
        return response()->json($data);
    }
}
