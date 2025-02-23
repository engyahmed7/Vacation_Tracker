<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
   

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        // 'email',
        'password',
        // 'department_id'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function vacationRequests(): HasMany
    {
        return $this->hasMany(VacationRequest::class);
    }

    public function annualVacations()
    {
        return $this->hasMany(VacationRequest::class)->where('vacation_type_id', 2);
    }

    public function casualVacations()
    {
        return $this->hasMany(VacationRequest::class)->where('vacation_type_id', 1);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class, 'user_id');
    }

    public function vacation_types(): HasMany
    {
        return $this->hasMany(VacationType::class, 'user_id');
    }

    public function getDepartmentNameAttribute()
    {
        return $this->department_id ? Department::find($this->department_id)->name :'';
    }
    public function routeNotificationForMattermost()
    {
        return config('services.mattermost.webhook_url');
    }
}
