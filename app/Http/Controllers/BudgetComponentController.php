<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BudgetComponent;

class BudgetComponentController extends Controller
{
    public function getBudgetComponent()
    {
        $data = BudgetComponent::all();
        return response()->json($data);
    }
}
