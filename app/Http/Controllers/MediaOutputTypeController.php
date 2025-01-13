<?php

namespace App\Http\Controllers;

use App\Models\MediaOutputType;
use Illuminate\Http\Request;

class MediaOutputTypeController extends Controller
{
    public function getMediaOutputType()
    {
        $data = MediaOutputType::all();
        return response()->json($data);
    }
}
