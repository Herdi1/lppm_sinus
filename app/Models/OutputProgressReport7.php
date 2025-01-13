<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputProgressReport7 extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'status',
        'improvement_description',
        'proof_improvement',
    ];

    public function serviceProgressReport(){
        return $this->belongsTo(ServiceProgressReport::class, 'report_id');
    }
}
