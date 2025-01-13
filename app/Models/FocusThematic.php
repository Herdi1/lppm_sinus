<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FocusThematic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        
    ];

    public function comunityService(){
        return $this->hasMany(ComunityService::class);
    }
}
