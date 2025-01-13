<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function research(){
        return $this->hasMany(Research::class);
    }

    public function statusChanges()
    {
        return $this->hasMany(ResearchStatusChange::class);
    }

    public function comunityService(){
        return $this->hasMany(ComunityService::class);
    }

    public function statusChangesService()
    {
        return $this->hasMany(ServiceStatusChange::class);
    }
}
