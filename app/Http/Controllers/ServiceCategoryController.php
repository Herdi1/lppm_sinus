<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    public function getServiceCategory()
    {
        $data = ServiceCategory::all();
        return response()->json($data);
    }
}
