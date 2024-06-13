<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserTask extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'task_name',
        'leader_name',
        'start_date',
        'end_date',
        'target',
        'progress',
    ];

    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }
}
