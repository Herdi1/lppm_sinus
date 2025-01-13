<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputFinalReport5 extends Model
{
    use HasFactory;

    protected $fillable = [
        'final_report_id',
        'status',
        'type_media',
        'title',
        'name',
        'proof_support',
    ];

    public function serviceFinalReport(){
        return $this->belongsTo(ServiceFinalReport::class, 'final_report_id');
    }
}
