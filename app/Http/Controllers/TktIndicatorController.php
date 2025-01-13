<?php

namespace App\Http\Controllers;

use App\Models\TktIndicator;
use Illuminate\Http\Request;

class TktIndicatorController extends Controller
{
    public function getTktIndicator($category_id, $level)
    {
        $data = TktIndicator::query()
                ->when($category_id, function($query)use($category_id){
                    return $query->where('category_id', $category_id);
                })
                ->when($level, function($query)use($level){
                    return $query->where('level', $level);
                })
                ->get();
        return response()->json($data);
    }
}
