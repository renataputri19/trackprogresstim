<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tim extends Model
{
    protected $fillable = ['name'];

    public function tasks()
    {
        return $this->hasMany(TasksAssignment::class, 'tim_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'division_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'division_id');
    }
    
}
