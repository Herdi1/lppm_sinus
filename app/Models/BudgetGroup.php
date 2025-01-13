<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function budgetPlan(){
        return $this->hasMany(BudgetPlan::class);
    }
}
