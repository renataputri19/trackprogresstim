<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'is_admin',
        'is_it_staff',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userAssignments()
    {
        return $this->hasMany(UserAssignment::class, 'user_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function ticketsRequested()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function ticketsAssigned()
    {
        return $this->hasMany(Ticket::class, 'it_staff_id');
    }
}




