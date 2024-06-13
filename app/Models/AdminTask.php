<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_name',
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

