<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputProgressReport5 extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'status',
        'type_media',
        'title',
        'name',
        'proof_support',
    ];

    public function serviceProgressReport(){
        return $this->belongsTo(ServiceProgressReport::class, 'report_id');
    }
}
