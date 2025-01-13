<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputPublication extends Model
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
        return $this->belongsTo(ComunityService::class, 'comunity_service_id');
    }

    public function category(){
        return $this->belongsTo(PublicationOutputCategory::class, 'id_category_output');
    }

    public function type(){
        return $this->belongsTo(PublicationOutputType::class, 'id_type_output');
    }
}
