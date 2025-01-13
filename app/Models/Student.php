<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_id',
        'name',
        'nim',
        'address',
        'email',
        'phone',
        'prodi',
        'role',
        'task',
    ];

    public function researches()
    {
        return $this->belongsTo(Research::class, 'research_id');
    }

    public function comunityServices(){
        return $this->belongsToMany(ComunityService::class, 'service_student');
    }
}
