<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceFinalReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'comunity_service_id',
        'summary',
        'keyword',
        'substance',
        'partner_contribution',
        'target_partners',
        'productive_economic_society',
        'nonproductive_economic_society',
        'number_of_partners',
        'partner_education',
        'problem_areas',
        'distance_partners',
        'male_proposing_team',
        'female_proposing_team',
        'male_partners_team',
        'female_partners_team',
        'total_students',
        'male_student',
        'female_student',
        'implementation_activities',
        'implementation_time',
        'program_sustainability',
        'production_capacity_before_program',
        'production_capacity_after_program',
        'turnover_before_program',
        'turnover_after_program',
        'funding_sources',
        'funding_amount',
        'partner_role',
        'partner_role_active',
        'partner_role_passive',
        'government_local_role',
        'funding_contribution',
        'budget_use',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function outputs1(){
        return $this->hasMany(OutputFinalReport1::class, 'final_report_id');
    }

    public function outputs2(){
        return $this->hasMany(OutputFinalReport2::class, 'final_report_id');
    }

    public function outputs3(){
        return $this->hasMany(OutputFinalReport3::class, 'final_report_id');
    }

    public function outputs4(){
        return $this->hasMany(OutputFinalReport4::class, 'final_report_id');
    }

    public function outputs5(){
        return $this->hasMany(OutputFinalReport5::class, 'final_report_id');
    }

    public function outputs6(){
        return $this->hasMany(OutputFinalReport6::class, 'final_report_id');
    }

    public function outputs7(){
        return $this->hasMany(OutputFinalReport7::class, 'final_report_id');
    }

    public function outputs8(){
        return $this->hasMany(OutputFinalReport8::class, 'final_report_id');
    }

    public function outputs9(){
        return $this->hasMany(OutputFinalReport9::class, 'final_report_id');
    }

    public function outputs10(){
        return $this->hasMany(OutputFinalReport10::class, 'final_report_id');
    }

    public function comunityService()
    {
        return $this->belongsTo(ComunityService::class, 'comunity_service_id');
    }
}
