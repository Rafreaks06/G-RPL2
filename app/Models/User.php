<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use App\Notifications\VerifyEmailNotification;

use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'email_verified_at',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(
            new VerifyEmailNotification
        );
    }

    public function assessor()
    {
        return $this->hasOne(
            Assessor::class
        );
    }

    public function staffRpl()
    {
        return $this->hasOne(
            StaffRpl::class
        );
    }

    public function committee()
    {
        return $this->hasOne(
            Committee::class
        );
    }

    public function applicant()
    {
        return $this->hasOne(
            Applicant::class
        );
    }
}