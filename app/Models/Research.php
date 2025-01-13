<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Research extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'tkt_current',
        'tkt_final',
        'scheme_id',
        'scope_id',
        'category_id',
        'focus_id',
        'theme_id',
        'topic_id',
        'cluster_lv1',
        'cluster_lv2',
        'cluster_lv3',
        'priority_id',
        'year',
        'duration',
        'leader_name',
        'leader_task',
        'substance_id',
        'substance',
        'approved_funds', //dana disetujui
        'letter_of_intent', //surat kesanggupan
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
            // $model->id = IdGenerator::generate(['table' => 'research', 'length' => 15, 'prefix' => 'RES-' . $model->user_id . '-' . date('Ymd') . '-', 'reset_on_prefix_change' => true]);

            $prefix = 'RES-' . $model->user_id . '-' . date('Ymd') . '-';

            $lastId = Research::where('id', 'like', $prefix . '%')
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'research_user')->withPivot('task', 'research_roles', 'status')->withTimestamps();
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'research_id');
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class, 'scheme_id');
    }

    public function scope()
    {
        return $this->belongsTo(Scope::class, 'scope_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function researchFocus()
    {
        return $this->belongsTo(ResearchFocus::class, 'focus_id');
    }

    public function researchTheme()
    {
        return $this->belongsTo(ResearchTheme::class, 'theme_id');
    }

    public function researchTopic()
    {
        return $this->belongsTo(ResearchTopic::class, 'topic_id');
    }

    public function substances()
    {
        return $this->belongsTo(Substance::class, 'substance_id');
    }

    public function scienceCluster1()
    {
        return $this->belongsTo(ScienceCluster1::class, 'cluster_lv1');
    }

    public function scienceCluster2()
    {
        return $this->belongsTo(ScienceCluster2::class, 'cluster_lv2');
    }

    public function scienceCluster3()
    {
        return $this->belongsTo(ScienceCluster3::class, 'cluster_lv3');
    }

    public function researchPriority()
    {
        return $this->belongsTo(ResearchPriority::class, 'priority_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status');
    }

    public function period()
    {
        return $this->belongsTo(ActivityPeriod::class, 'period_id');
    }

    public function output()
    {
        return $this->hasMany(Output::class, 'research_id', 'id');
    }

    public function budgetPlan()
    {
        return $this->hasMany(BudgetPlan::class);
    }

    public function supportingDocument()
    {
        return $this->hasMany(SupportingDocument::class);
    }

    public function statusChanges()
    {
        return $this->hasMany(ResearchStatusChange::class);
    }

    public function reviewers()
    {
        return $this->belongsToMany(User::class, 'research_reviewer');
    }

    public function reviews()
    {
        return $this->hasMany(ResearchReview::class);
    }

    public function logbooks()
    {
        return $this->morphMany(Logbook::class, 'loggable');
    }

    public function progressReport()
    {
        return $this->hasMany(ProgressReport::class);
    }

    public function researchMonev()
    {
        return $this->hasMany(ResearchMonev::class);
    }

    public function finalReport()
    {
        return $this->hasMany(ResearchFinalReport::class);
    }
}
