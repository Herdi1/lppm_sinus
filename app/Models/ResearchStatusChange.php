<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchStatusChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_id',
        'prev_status_id',
        'new_status_id',
        'notes',
        'user_id'
    ];

    public function prevStatus()
    {
        return $this->belongsTo(Status::class, 'prev_status_id');
    }

    public function newStatus()
    {
        return $this->belongsTo(Status::class, 'new_status_id');
    }

    public function research()
    {
        return $this->belongsTo(Research::class, 'research_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
