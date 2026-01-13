<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SbrBusiness extends Model
{
    use HasFactory;

    protected $table = 'sbr_businesses';

    protected $fillable = [
        'nama_usaha',
        'kecamatan',
        'kelurahan',
        'idsbr',
        'alamat',
        'latitude',
        'longitude',
        'status'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    /**
     * Scope to filter by kecamatan
     */
    public function scopeByKecamatan($query, $kecamatan)
    {
        if ($kecamatan) {
            return $query->where('kecamatan', $kecamatan);
        }
        return $query;
    }

    /**
     * Scope to filter by kelurahan
     */
    public function scopeByKelurahan($query, $kelurahan)
    {
        if ($kelurahan) {
            return $query->where('kelurahan', $kelurahan);
        }
        return $query;
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope to search by nama_usaha
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('nama_usaha', 'like', '%' . $search . '%');
        }
        return $query;
    }

    /**
     * Check if the record has been tagged (has coordinates)
     */
    public function isTagged(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    /**
     * Get distinct kecamatan values from the database
     */
    public static function getDistinctKecamatan()
    {
        return self::select('kecamatan')
            ->distinct()
            ->orderBy('kecamatan')
            ->pluck('kecamatan');
    }

    /**
     * Get distinct kelurahan values for a specific kecamatan
     */
    public static function getDistinctKelurahan($kecamatan)
    {
        return self::where('kecamatan', $kecamatan)
            ->select('kelurahan')
            ->distinct()
            ->orderBy('kelurahan')
            ->pluck('kelurahan');
    }
}

