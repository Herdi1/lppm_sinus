<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'tkt_target',
        'name',
    ];

    public function research(){
        return $this->hasMany(Research::class);
    }
}
