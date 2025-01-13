<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputProgressReport1 extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'status',
        'recognized_sks',
        'recognized_courses',
        'proof_recognition'
    ];

    public function serviceProgressReport(){
        return $this->belongsTo(ServiceProgressReport::class, 'report_id');
    }
}
