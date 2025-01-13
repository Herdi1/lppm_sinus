<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasRoles, HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nidn',
        'cluster',
        'institution',
        'id_prodi',
        'education_level',
        'position',
        'address',
        'place_of_birth',
        'date_of_birth',
        'nik',
        'phone',
        'website',
        // 'sinta_id',
        // 'ss_overall_v2',
        // 'ss_3yr_v2',
        // 'ss_overall_v3',
        // 'ss_3yr_v3',
        // 'affiliate_overall_v3',
        // 'affiliate_3yr_v3',
        // 'scopus_docs',
        // 'scopus_cite',
        // 'scopus_cite_docs',
        // 'h_index_scopus',
        // 'g_index_scopus',
        // 'i10_index_scopus',
        // 'gs_docs',
        // 'gs_cite',
        // 'gs_cite_docs',
        // 'h_index_gs',
        // 'g_index_gs',
        // 'i10_index_gs',
        // 'wos_docs',
        // 'wos_cite',
        // 'wos_cite_docs',
        // 'h_index_wos',
        // 'g_index_wos',
        // 'i10_index_wos',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function activity_detail()
    {
        return $this->hasOne(ActivityDetail::class, 'id_kepala');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    public function research()
    {
        return $this->hasMany(Research::class);
    }

    public function researches()
    {
        return $this->belongsToMany(Research::class, 'research_user');
    }

    public function status_changes()
    {
        return $this->hasMany(ResearchStatusChange::class);
    }

    public function reviewedResearches()
    {
        return $this->belongsToMany(Research::class, 'research_reviewer');
    }

    public function reviewedComunityServices()
    {
        return $this->belongsToMany(ComunityService::class, 'service_reviewer');
    }

    public function reviews()
    {
        return $this->hasMany(ResearchReview::class);
    }

    public function serviceReviews()
    {
        return $this->hasMany(ServiceReview::class);
    }

    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }

    public function serviceLogbook()
    {
        return $this->hasMany(ServiceLogbook::class);
    }

    public function serviceProgressReport()
    {
        return $this->hasMany(ServiceProgressReport::class);
    }

    public function serviceFinalReport()
    {
        return $this->hasMany(ServiceFinalReport::class);
    }

    public function researchMonev()
    {
        return $this->hasMany(ResearchMonev::class);
    }

    public function serviceMonev()
    {
        return $this->hasMany(ServiceMonev::class);
    }

    public function progressReport()
    {
        return $this->hasMany(ProgressReport::class);
    }
    public function finalReport()
    {
        return $this->hasMany(ResearchFinalReport::class);
    }

    public function service()
    {
        return $this->hasMany(ComunityService::class);
    }

    public function services()
    {
        return $this->belongsToMany(ComunityService::class, 'comunity_services_user');
    }

    public function period()
    {
        return $this->hasMany(ActivityPeriod::class);
    }
}
