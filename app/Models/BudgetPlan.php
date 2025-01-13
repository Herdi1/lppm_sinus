<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_id',
        'year',
        'id_group_budget',
        'id_component_budget',
        'item',
        'unit',
        'volume',
        'price_unit',
        'total',
    ];

    public function research(){
        return $this->belongsTo(Research::class, 'research_id');
    }

    public function budgetComponent(){
        return $this->belongsTo(BudgetComponent::class, 'id_component_budget');
    }

    public function budgetGroup(){
        return $this->belongsTo(BudgetGroup::class, 'id_group_budget');
    }
}
