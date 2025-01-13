<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportingFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'comunity_service_id',
        'type_id',
        'document'
    ];

    public function comunityService(){
        return $this->hasMany(ComunityService::class);
    }
}
