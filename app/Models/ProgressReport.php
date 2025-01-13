<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'research_id',
        'summary',
        'keyword',
        'substance',
        'partner_contribution',
        'sptb',
        'no_sk',
        'no_contract',
        'place_date',
        'nip',
        'description_1',
        'realization_1',
        'description_2',
        'realization_2',
        'description_3',
        'realization_3',
        'description_4',
        'realization_4',
        'description_5',
        'realization_5',
        'description_6',
        'realization_6',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function research()
    {
        return $this->belongsTo(Research::class, 'research_id');
    }

    public function outputs()
    {
        return $this->hasMany(OutputResult::class, 'report_id');
    }
}
