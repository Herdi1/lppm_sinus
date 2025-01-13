<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'user',
        'type'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user');
    }

    public function research()
    {
        return $this->hasMany(Research::class, 'research_period_id')->where('type', 'research');
    }
    public function communityService()
    {
        return $this->hasMany(ComunityService::class, 'research_period_id')->where('type', 'community_service');
    }
}
