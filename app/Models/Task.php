<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
        'name',
        'team_leader',
        'start_date',
        'end_date',
        'target',
        'progress',
        'user_id', // Add user_id to the fillable array
    ];

    // Define the percentage attribute as an accessor
    protected $appends = ['percentage'];

    public function getPercentageAttribute()
    {
        if ($this->target == 0) {
            return 0;
        }
        return ($this->progress / $this->target) * 100;
    }

    // Define the relationship with the User model
    public function user()
    {
        // A task belongs to a user
        return $this->belongsTo(User::class);
    }
}
