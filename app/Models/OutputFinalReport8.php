<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputFinalReport8 extends Model
{
    use HasFactory;

    protected $fillable = [
        'final_report_id',
        'presentation',
    ];

    public function serviceFinalReport(){
        return $this->belongsTo(ServiceFinalReport::class, 'final_report_id');
    }
}
