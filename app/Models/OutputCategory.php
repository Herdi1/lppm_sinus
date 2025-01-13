<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheme_id',
        'name',
    ];

    public function output()
    {
        return $this->hasMany(Output::class);
    }
}
