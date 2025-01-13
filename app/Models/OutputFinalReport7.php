<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputFinalReport7 extends Model
{
    use HasFactory;

    protected $fillable = [
        'final_report_id',
        'status',
        'improvement_description',
        'proof_improvement',
    ];

    public function serviceFinalReport(){
        return $this->belongsTo(ServiceFinalReport::class, 'final_report_id');
    }
}
