<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function userTasks()
    {
        return $this->hasMany(UserTask::class);
    }

    public function adminTasks()
    {
        return $this->hasMany(AdminTask::class, 'leader_name', 'name');
    }
}
