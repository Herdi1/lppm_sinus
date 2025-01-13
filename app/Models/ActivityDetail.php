<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity',
        'no_sk_lembaga',
        'nama_lembaga',
        'alamat_lembaga',
        'no_telp',
        'no_fax',
        'email',
        'website',
        'id_kepala'
    ];

    public function activity_leader()
    {
        return $this->belongsTo(User::class, 'id_kepala');
    }
}
