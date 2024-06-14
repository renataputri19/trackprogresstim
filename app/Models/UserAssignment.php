<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id', 'user_id', 'target', 'progress',
    ];

    public function task()
    {
        return $this->belongsTo(TasksAssignment::class, 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
