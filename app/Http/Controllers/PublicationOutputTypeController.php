<?php

namespace App\Http\Controllers;

use App\Models\PublicationOutputType;
use Illuminate\Http\Request;

class PublicationOutputTypeController extends Controller
{
    public function getPublicationOutputType()
    {
        $data = PublicationOutputType::all();
        return response()->json($data);
    }
}
