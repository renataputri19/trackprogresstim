<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['division_id', 'name', 'slug', 'description'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function documents()
    {
        return $this->hasMany(ActivityDocument::class);
    }
}
