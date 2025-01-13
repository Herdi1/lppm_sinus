<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchTheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'focus_id',
    ];

    public function research(){
        return $this->hasMany(Research::class);
    }
}
