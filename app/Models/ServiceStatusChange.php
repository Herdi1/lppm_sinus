<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStatusChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'comunity_service_id',
        'prev_status_id',
        'new_status_id',
        'notes'
    ];

    public function prevStatus()
    {
        return $this->belongsTo(Status::class, 'prev_status_id');
    }

    // Relation to Status model for new status
    public function newStatus()
    {
        return $this->belongsTo(Status::class, 'new_status_id');
    }

    // Relation to Research model
    public function comunityService()
    {
        return $this->belongsTo(ComunityService::class, 'research_id');
    }
}
