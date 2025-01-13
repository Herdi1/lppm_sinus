<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'comunity_service_id',
        'reviewer_id',
        'indicator_1',
        'indicator_2',
        'indicator_3',
        'indicator_4',
        'indicator_5',
        'indicator_6',
        'indicator_7',
        'indicator_8',
        'indicator_9',
        'indicator_10',
        'indicator_11',
        'indicator_12',
        'substance_score_1',
        'substance_score_2',
        'substance_score_3',
        'substance_score_4',
        'substance_score_5',
        'substance_score_6',
        'substance_score_7',
        'substance_score_8',
        'substance_score_9',
        'substance_score_10',
        'substance_score_11',
        'substance_score_12',
        'substance_score_13',
        'substance_score_14',
        'substance_score_15',
        'substance_score_16',
        'substance_score_17',
        'substance_score_18',
        'notes',
    ];

    public function ComunityService(){
        return $this->belongsTo(ComunityService::class, 'comunity_service_id');
    }
    public function reviewer(){
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
