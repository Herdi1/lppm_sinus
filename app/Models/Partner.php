<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'comunity_service_id',
        'name',
        'province',
        'leader_name',
        'group_id',
        'partner_type_id',
        'email',
        'funding_contribution',
        'document'
    ];

    public function comunityService(){
        return $this->hasMany(ComunityService::class);
    }
}
