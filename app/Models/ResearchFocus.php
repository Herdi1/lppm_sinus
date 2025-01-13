<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchFocus extends Model
{
    use HasFactory;

    protected $table = 'research_focus';
    
    protected $fillable = [
        'name',
    ];

    public function research(){
        return $this->hasMany(Research::class);
    }
}
