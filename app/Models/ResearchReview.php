<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_id',
        'reviewer_id',
        'indicator_1',
        'indicator_2',
        'indicator_3',
        'indicator_4',
        'indicator_5',
        'indicator_6',
        'substance_score_1_1',
        'substance_score_1_2',
        'substance_score_1_3',
        'substance_score_2_1',
        'substance_score_2_2',
        'substance_score_2_3',
        'substance_score_3_1',
        'substance_score_3_2',
        'substance_score_3_3',
        'substance_score_4_1',
        'substance_score_4_2',
        'notes',
    ];

    public function research(){
        return $this->belongsTo(Research::class, 'research_id');
    }
    public function reviewer(){
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
