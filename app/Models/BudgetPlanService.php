<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetPlanService extends Model
{
    use HasFactory;

    protected $fillable = [
        'comunity_service_id',
        'year',
        'id_group_budget',
        'id_component_budget',
        'item',
        'unit',
        'volume',
        'price_unit',
        'total',
    ];

    public function comunityService(){
        return $this->belongsTo(ComunityService::class, 'comunity_service_id');
    }

    public function budgetComponent(){
        return $this->belongsTo(BudgetComponent::class, 'id_component_budget');
    }

    public function budgetGroup(){
        return $this->belongsTo(BudgetGroup::class, 'id_group_budget');
    }
}
