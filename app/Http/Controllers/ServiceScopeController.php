<?php

namespace App\Http\Controllers;

use App\Models\ServiceScheme;
use Illuminate\Http\Request;

class ServiceScopeController extends Controller
{
    public function getServiceScheme()
    {
        $data = ServiceScheme::all();
        return response()->json($data);
    }
}
