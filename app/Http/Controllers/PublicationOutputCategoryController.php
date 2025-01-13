<?php

namespace App\Http\Controllers;

use App\Models\PublicationOutputCategory;
use Illuminate\Http\Request;

class PublicationOutputCategoryController extends Controller
{
    public function getPublicationOutputCategory()
    {
        $data = PublicationOutputCategory::all();
        return response()->json($data);
    }
}
