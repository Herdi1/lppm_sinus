<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputFinalReport4 extends Model
{
    use HasFactory;

    protected $fillable = [
        'final_report_id',
        'status_article',
        'status_writer',
        'journal_name',
        'issn_eissn',
        'indexing_agency',
        'journal_url',
        'title_article',
        'manuscript_article',
        'proof_submit',
    ];

    public function serviceFinalReport(){
        return $this->belongsTo(ServiceFinalReport::class, 'final_report_id');
    }
}
