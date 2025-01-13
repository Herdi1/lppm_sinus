<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'comunity_service_id',
        'year',
        'id_category_output',
        'id_type_output',
        'status',
        'description'
    ];

    public function comunityService(){
        return $this->belongsTo(ComunityService::class, 'comunity_service_id', 'id');
    }

    public function category(){
        return $this->belongsTo(PartnerOutputCategory::class, 'id_category_output');
    }

    public function type(){
        return $this->belongsTo(PartnerOutputType::class, 'id_type_output');
    }
}
