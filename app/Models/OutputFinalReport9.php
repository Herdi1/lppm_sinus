<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputFinalReport9 extends Model
{
    use HasFactory;

    protected $fillable = [
        'final_report_id',
        'result_description',
        'result_plans',
    ];

    public function serviceFinalReport(){
        return $this->belongsTo(ServiceFinalReport::class, 'final_report_id');
    }
}
