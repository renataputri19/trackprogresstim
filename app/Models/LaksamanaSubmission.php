<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaksamanaSubmission extends Model
{
    use HasFactory;

    protected $table = 'laksamana_submissions';

    protected $fillable = [
        'laksamana_user_id',
        'sbr_business_id',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(LaksamanaUser::class, 'laksamana_user_id');
    }

    public function business()
    {
        return $this->belongsTo(SbrBusiness::class, 'sbr_business_id');
    }
}