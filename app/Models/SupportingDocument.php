<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportingDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_id',
        'partner_name',
        'email',
        'institution',
        'country_code',
        'institution_address',
        'funding_contribution1',
        'funding_contribution2',
        'document'
    ];

    public function research(){
        return $this->belongsTo(Research::class, 'research_id');
    }
}
