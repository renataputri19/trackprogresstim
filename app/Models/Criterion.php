<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criterion extends Model
{
    use HasFactory;

    protected $fillable = [
        'penilaian',
        'kriteria_nilai',
        'pilihan_jawaban',
        'jawaban_unit',
        'nilai_unit',
        'catatan_unit',
        'bukti_dukung_unit',
        'url_bukti_dukung',
        'jawaban_tpi',
        'nilai_tpi',
        'catatan_reviu_tpi',
        'category',
        'last_updated_by',
    ];
}
