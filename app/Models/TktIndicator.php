<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TktIndicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'level',
        'description'
    ];

    public function category(){
        return $this->belongsTo(TktCategory::class, 'category_id');
    }
}
