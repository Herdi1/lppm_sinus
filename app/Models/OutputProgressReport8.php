<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputProgressReport8 extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'presentation',
    ];

    public function serviceProgressReport(){
        return $this->belongsTo(ServiceProgressReport::class, 'report_id');
    }
}
