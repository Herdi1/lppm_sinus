<?php

namespace App\Http\Controllers;

use App\Models\BudgetGroup;
use Illuminate\Http\Request;

class BudgetGroupController extends Controller
{
    public function getBudgetGroup()
    {
        $data = BudgetGroup::all();
        return response()->json($data);
    }
}
