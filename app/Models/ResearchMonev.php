<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchMonev extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_id',
        'comment1',
        'comment2',
        'comment3',
        'comment4',
        'comment5',
        'comment6',
        'score1',
        'score2',
        'score3',
        'score4',
        'score5',
        'reviewer_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function research()
    {
        return $this->belongsTo(Research::class, 'research_id');
    }
}
