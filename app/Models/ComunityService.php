<?php

namespace App\Models;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComunityService extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'category_id',
        'focus_thematic_id',
        'focus_rirn_id',
        'scheme_id',
        'scope_id',
        'year',
        'duration',
        'cluster_lv1',
        'cluster_lv2',
        'cluster_lv3',
        'leader_name',
        'leader_task',
        'substance_document',
        'approved_funds',
        'letter_of_intent',
        'status',
        'period_id',
        'progress_report_deadline',
        'final_report_deadline',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            // $model->id = IdGenerator::generate(['table' => 'comunity_services', 'length' => 15, 'prefix' => 'COMSER-' . $model->user_id . '-' . date('Ymd') . '-', 'reset_on_prefix_change' => true]);

            $prefix = 'COMSER-' . $model->user_id . '-' . date('Ymd') . '-';

            $lastId = ComunityService::where('id', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();

            if ($lastId) {
                $lastNumber = (int) str_replace($prefix, '', $lastId->id);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $model->id = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        });
    }


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function members(){
        return $this->belongsToMany(User::class, 'comunity_services_user')->withPivot('task')->withTimestamps();
    }

    public function students(){
        return $this->belongsToMany(Student::class, 'service_student')->withPivot('task')->withTimestamps();
    }

    public function category(){
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function focusThematic(){
        return $this->belongsTo(FocusThematic::class, 'focus_thematic_id');
    }

    public function focusRirn(){
        return $this->belongsTo(FocusRIRN::class, 'focus_rirn_id');
    }

    public function scheme(){
        return $this->belongsTo(ServiceScheme::class,'scheme_id');
    }

    public function scope(){
        return $this->belongsTo(ServiceScope::class,'scope_id');
    }

    public function clusterLv1(){
        return $this->belongsTo(ScienceCluster1::class,'cluster_lv1');
    }

    public function clusterLv2(){
        return $this->belongsTo(ScienceCluster2::class,'cluster_lv2');
    }

    public function clusterLv3(){
        return $this->belongsTo(ScienceCluster3::class,'cluster_lv3');
    }

    public function outputPartner()
    {
        return $this->hasMany(OutputPartner::class, 'comunity_service_id');
    }

    public function outputPublication()
    {
        return $this->hasMany(OutputPublication::class, 'comunity_service_id', 'id');
    }

    public function outputMedia()
    {
        return $this->hasMany(OutputMediaPublication::class, 'comunity_service_id', 'id');
    }

    public function outputVideo()
    {
        return $this->hasMany(OutputVideo::class, 'comunity_service_id', 'id');
    }

    public function budgetPlanService()
    {
        return $this->hasMany(BudgetPlanService::class);
    }

    public function partner()
    {
        return $this->hasMany(Partner::class);
    }

    public function supportingFile()
    {
        return $this->hasMany(SupportingFile::class);
    }

    public function serviceLogbook()
    {
        return $this->hasMany(ServiceLogbook::class);
    }

    public function reviewers()
    {
        return $this->belongsToMany(User::class, 'service_reviewer');
    }

    public function serviceReviews()
    {
        return $this->hasMany(ServiceReview::class);
    }

    public function serviceProgressReport()
    {
        return $this->hasMany(ServiceProgressReport::class);
    }

    public function serviceMonev()
    {
        return $this->hasMany(ServiceMonev::class);
    }

    public function serviceFinalReport()
    {
        return $this->hasMany(ServiceFinalReport::class);
    }

    public function period()
    {
        return $this->belongsTo(ActivityPeriod::class, 'period_id');
    }

}
