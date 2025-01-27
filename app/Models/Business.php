<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = [
        'nama_usaha',
        'deskripsi_usaha',
        'alamat',
        'latitude',
        'longitude',
        'tagging_time'
    ];

    protected $casts = [
        'tagging_time' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];
}
