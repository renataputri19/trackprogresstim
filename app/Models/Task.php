<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Specify the fields that can be mass assigned
    protected $fillable = [
        'name',
        'team_leader',
        'start_date',
        'end_date',
        'target',
        'progress',
        'user_id', // Add user_id to the fillable array
    ];

    // Append the percentage attribute to the model's array form
    protected $appends = ['percentage'];

    // Accessor for the percentage attribute
    public function getPercentageAttribute()
    {
        if ($this->target == 0) {
            return 0;
        }
        // Calculate the percentage based on progress and target
        return ($this->progress / $this->target) * 100;
    }

    // Define the relationship with the User model
    public function user()
    {
        // A task belongs to a user
        return $this->belongsTo(User::class);
    }
}
