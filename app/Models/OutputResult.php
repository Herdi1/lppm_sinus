<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'status_article',
        'status_writer',
        'journal_name',
        'issn',
        'indexing_agency',
        'journal_url',
        'title_article',
        'manuscript_article',
        'proof_submit'
    ];

    public function progressReport(){
        return $this->belongsTo(ProgressReport::class, 'report_id');
    }
}
