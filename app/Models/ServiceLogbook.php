<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLogbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'comunity_service_id',
        'date_activity',
        'group_budget',
        'nominal',
        'file_number',
        'activity_description',
        'percentage',
        'document'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comunityService()
    {
        return $this->belongsTo(ComunityService::class, 'comunity_service_id');
    }
}
