<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoOutputCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        
    ];

    public function output(){
        return $this->hasMany(OutputVideo::class);
    }
}
