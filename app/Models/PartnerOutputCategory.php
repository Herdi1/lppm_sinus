<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerOutputCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        
    ];

    public function outputPartner(){
        return $this->hasMany(OutputPartner::class);
    }
}
