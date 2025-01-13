<?php

namespace App\Http\Controllers;

use App\Models\PartnerOutputType;
use Illuminate\Http\Request;

class PartnerOutputTypeController extends Controller
{
    public function getPartnerOutputType()
    {
        $data = PartnerOutputType::all();
        return response()->json($data);
    }
}
