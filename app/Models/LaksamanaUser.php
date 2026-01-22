<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class LaksamanaUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'laksamana_users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function submissions()
    {
        return $this->hasMany(LaksamanaSubmission::class, 'laksamana_user_id');
    }
}