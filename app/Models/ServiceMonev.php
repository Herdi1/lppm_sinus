<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceMonev extends Model
{
    use HasFactory;

    protected $fillable = [
        'comunity_service_id',
        'presence_1',
        'presence_2',
        'presence_3',
        'presence_4',
        'presence_5',
        'article_publication',
        'publication_journal',
        'recognition_sks_1',
        'recognition_sks_2',
        'video_1',
        'video_2',
        'video_3',
        'video_4',
        'video_5',
        'video_6',
        'video_7',
        'video_8',
        'poster_1',
        'poster_2',
        'poster_3',
        'budget_usage_1',
        'budget_usage_2',
        'budget_usage_3',
        'empowerment_1',
        'empowerment_2',
        'empowerment_3',
        'empowerment_4',
        'empowerment_5',
        'reviewer_note',
        
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
