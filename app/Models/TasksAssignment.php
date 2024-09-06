<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasksAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'leader_id', 'name', 'start_date', 'end_date', 'target', 'progress_total', 'tim_id'
    ];

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function userAssignments()
    {
        return $this->hasMany(UserAssignment::class, 'task_id');
    }

    public function tim()
    {
        return $this->belongsTo(Tim::class, 'tim_id');
    }
       
}


