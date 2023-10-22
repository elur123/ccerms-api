<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\RoleEnum;
use App\Enums\StatusEnum;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status_id',
        'mobile_number',
        'image_url',
        'address',
        'can_advise',
        'can_panel',
        'can_teach'
    ];

    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Scope functions
     */
    public function scopeStudent(Builder $query): void
    {
        $query->where('role_id', RoleEnum::STUDENT->value);
    }
    public function scopeApproved(Builder $query): void
    {
        $query->where('status_id', StatusEnum::APPROVED->value);
    }
    public function scopeFilter($query, $keyword)
    {
        if (! $keyword) {
            return $query;
        }

        $attributes = [
            'users.name',
            'users.email',
        ];

        $attributes = implode(', ', $attributes);

        return $query->whereRaw("
            (CONCAT_WS(' ', {$attributes}) like '%{$keyword}%')
        ");
    }

    /**
     * 
     * Relationship functions
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function studentDetails()
    {
        return $this->hasOne(StudentDetail::class, 'user_id');
    }

    public function emailVerification()
    {
        return $this->hasOne(EmailVerification::class, 'user_id');
    }
}
