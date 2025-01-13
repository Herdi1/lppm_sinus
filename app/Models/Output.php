<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Output extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_id',
        'year',
        'id_category_output',
        'id_type_output',
        'status',
        'description'
    ];

    public function research(){
        return $this->belongsTo(Research::class, 'research_id', 'id');
    }

    public function category(){
        return $this->belongsTo(OutputCategory::class, 'id_category_output');
    }

    public function type(){
        return $this->belongsTo(OutputType::class, 'id_type_output');
    }
}
