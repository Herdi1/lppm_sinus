<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScienceCluster1 extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function research(){
        return $this->hasMany(Research::class);
    }

    public function comunityService(){
        return $this->hasMany(ComunityService::class);
    }
}
