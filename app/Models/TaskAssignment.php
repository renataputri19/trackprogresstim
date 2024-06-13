<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_name',
        'leader_name',
        'start_date',
        'end_date',
        'user_id',
        'target',
    ];

    public function adminTask()
    {
        return $this->belongsTo(AdminTask::class);
    }

    public function userTask()
    {
        return $this->belongsTo(UserTask::class);
    }
}

