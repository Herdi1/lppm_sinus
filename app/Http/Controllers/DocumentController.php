<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    
    public function downloadDocument($filePath){
        if(Storage::disk('public')->exists($filePath)){

            $absolutePath = storage_path("app/public/{$filePath}");

            if(strpos(realpath($absolutePath), storage_path('app/public')) === 0){
                return response()->download($absolutePath);
            }

            // if (Storage::disk('public')->exists($filePath)) {
            //     return response()->download(storage_path("app/public/{$filePath}"));
            // }
        }

        return response()->json(['error' => 'Document Not Found!'], 404);
    }
}
