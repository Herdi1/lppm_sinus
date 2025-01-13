<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProgressReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'comunity_service_id',
        'summary',
        'keyword',
        'substance',
        'partner_contribution',
        'budget_use',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function outputs1(){
        return $this->hasMany(OutputProgressReport1::class, 'report_id');
    }

    public function outputs2(){
        return $this->hasMany(OutputProgressReport2::class, 'report_id');
    }

    public function outputs3(){
        return $this->hasMany(OutputProgressReport3::class, 'report_id');
    }

    public function outputs4(){
        return $this->hasMany(OutputProgressReport4::class, 'report_id');
    }

    public function outputs5(){
        return $this->hasMany(OutputProgressReport5::class, 'report_id');
    }

    public function outputs6(){
        return $this->hasMany(OutputProgressReport6::class, 'report_id');
    }

    public function outputs7(){
        return $this->hasMany(OutputProgressReport7::class, 'report_id');
    }

    public function outputs8(){
        return $this->hasMany(OutputProgressReport8::class, 'report_id');
    }

    public function outputs9(){
        return $this->hasMany(OutputProgressReport9::class, 'report_id');
    }

    public function outputs10(){
        return $this->hasMany(OutputProgressReport10::class, 'report_id');
    }

    public function comunityService()
    {
        return $this->belongsTo(ComunityService::class, 'comunity_service_id');
    }
}
