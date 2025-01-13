<?php

namespace App\Http\Controllers;

use App\Models\VideoOutputType;
use Illuminate\Http\Request;

class VideoOutputTypeController extends Controller
{
    public function getVideoOutputType()
    {
        $data = VideoOutputType::all();
        return response()->json($data);
    }
}
