<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'timezone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    public function businessProfile()
    {
        return $this->hasOne(BusinessProfile::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function adAccounts()
    {
        return $this->hasMany(AdAccount::class);
    }

    public function adPerformanceSnapshots()
    {
        return $this->hasMany(AdPerformanceSnapshot::class);
    }

    public function cmoBriefs()
    {
        return $this->hasMany(CmoBrief::class);
    }

    public function campaignPlans()
    {
        return $this->hasMany(CampaignPlan::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function aiCalls()
    {
        return $this->hasMany(AiCall::class);
    }
}
