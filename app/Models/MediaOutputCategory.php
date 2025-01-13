<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaOutputCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        
    ];

    public function outputMedia(){
        return $this->hasMany(OutputMediaPublication::class);
    }
}
