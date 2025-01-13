<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputFinalReport1 extends Model
{
    use HasFactory;

    protected $fillable = [
        'final_report_id',
        'status',
        'recognized_sks',
        'recognized_courses',
        'proof_recognition'
    ];

    public function serviceFinalReport(){
        return $this->belongsTo(ServiceFinalReport::class, 'final_report_id');
    }
}
