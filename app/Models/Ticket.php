<?php

// app/Models/Ticket.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_code', 'user_id', 'ruangan', 'title', 'description', 'requestor_photo',
        'it_staff_id', 'it_photo', 'status', 'done_at', 'public_token', 'service_type',
        'category', 'map_type', 'zone', 'kdkec', 'nmkec', 'kddesa', 'nmdesa',
    ];

    protected $casts = [
        'done_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function requestor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function itStaff()
    {
        return $this->belongsTo(User::class, 'it_staff_id');
    }

    // Accessor for requestor name
    public function getRequestorNameAttribute()
    {
        return $this->requestor ? $this->requestor->name : 'Unknown Requestor';
    }

    // Static method to generate ticket code
    public static function generateTicketCode($category = null)
    {
        $yearMonth = now()->format('Ym'); // e.g., 202503 for March 2025

        // Determine prefix based on category
        if ($category === 'Peta Cetak') {
            $prefix = 'MAP-' . $yearMonth;
        } else {
            $prefix = 'TICKET-' . $yearMonth;
        }

        $lastTicket = self::where('ticket_code', 'like', $prefix . '-%')->orderBy('ticket_code', 'desc')->first();
        $lastNumber = $lastTicket ? (int) substr($lastTicket->ticket_code, -4) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . '-' . $newNumber;
    }

    // Generate public token for viewing
    public static function generatePublicToken()
    {
        do {
            $token = Str::random(32);
        } while (self::where('public_token', $token)->exists());

        return $token;
    }

    // Check if IT photo is required based on category and status
    public function isItPhotoRequired($status = null)
    {
        // Use provided status or current status
        $checkStatus = $status ?? $this->status;

        // For map requests (Peta Cetak), IT photo is required only when status is 'completed'
        if ($this->category === 'Peta Cetak' || $this->service_type === 'map_request') {
            return $checkStatus === 'completed';
        }

        // For all other categories, IT photo is optional
        return false;
    }

    // Check if requestor photo is required based on category
    public function isRequestorPhotoRequired()
    {
        // Peta Cetak (map requests) - requestor photo is optional
        if ($this->category === 'Peta Cetak') {
            return false;
        }

        // All other categories - requestor photo is required
        return true;
    }

    // Get category display name
    public function getCategoryDisplayAttribute()
    {
        return $this->category ?? 'Lainnya';
    }

    // Get service type display name (for backward compatibility)
    public function getServiceTypeDisplayAttribute()
    {
        return $this->service_type === 'map_request' ? 'Permintaan Peta' : 'Tiket IT';
    }

    // Get map type display name
    public function getMapTypeDisplayAttribute()
    {
        if ($this->category !== 'Peta Cetak' && $this->service_type !== 'map_request') {
            return null;
        }

        return $this->map_type === 'kecamatan' ? 'Peta Kecamatan' : 'Peta Kelurahan';
    }

    // Get location display (district and village)
    public function getLocationDisplayAttribute()
    {
        if ($this->category !== 'Peta Cetak' && $this->service_type !== 'map_request') {
            return null;
        }

        // Use new location fields if available
        if ($this->kdkec && $this->nmkec) {
            $location = $this->kdkec . ' - ' . $this->nmkec;
            if ($this->kddesa && $this->nmdesa) {
                $location .= ' / ' . $this->kddesa . ' - ' . $this->nmdesa;
            }
            return $location;
        }

        // Fallback to old zone field for backward compatibility
        return $this->zone ? 'Zona ' . $this->zone : null;
    }

    // Get district display
    public function getDistrictDisplayAttribute()
    {
        if ($this->kdkec && $this->nmkec) {
            return $this->kdkec . ' - ' . $this->nmkec;
        }
        return null;
    }

    // Get village display
    public function getVillageDisplayAttribute()
    {
        if ($this->kddesa && $this->nmdesa) {
            return $this->kddesa . ' - ' . $this->nmdesa;
        }
        return null;
    }

    // Get list of available categories
    public static function getCategories()
    {
        return [
            'Inovasi' => 'Inovasi',
            'Peta Cetak' => 'Peta Cetak',
            'Admin Fasih' => 'Admin Fasih',
            'Infrastruktur - Perawatan PC/Laptop' => 'Infrastruktur - Perawatan PC/Laptop',
            'Infrastruktur - Perawatan Printer' => 'Infrastruktur - Perawatan Printer',
            'Infrastruktur - Jaringan' => 'Infrastruktur - Jaringan',
            'Pengolahan - Susenas' => 'Pengolahan - Susenas',
            'Pengolahan - Seruti' => 'Pengolahan - Seruti',
            'Administrasi' => 'Administrasi',
            'Lainnya' => 'Lainnya',
        ];
    }
}
