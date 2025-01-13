<?php

namespace App\Http\Controllers;

use App\Models\PartnerOutputCategory;
use Illuminate\Http\Request;

class PartnerOutputCategoryController extends Controller
{
    public function getPartnerOutputCategory()
    {
        $data = PartnerOutputCategory::all();
        return response()->json($data);
    }
}
