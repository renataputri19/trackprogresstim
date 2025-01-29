<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityDocument extends Model
{
    protected $fillable = ['activity_id', 'title', 'document_date', 'onedrive_link', 'description'];

    protected $casts = [
        'document_date' => 'date',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
