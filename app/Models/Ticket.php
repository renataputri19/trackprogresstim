<?php

// app/Models/Ticket.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_code', 'user_id', 'ruangan', 'title', 'description', 'requestor_photo',
        'it_staff_id', 'it_photo', 'status', 'done_at',
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
    public static function generateTicketCode()
    {
        $yearMonth = now()->format('Ym'); // e.g., 202503 for March 2025
        $prefix = 'TICKET-' . $yearMonth;
        $lastTicket = self::where('ticket_code', 'like', $prefix . '-%')->orderBy('ticket_code', 'desc')->first();
        $lastNumber = $lastTicket ? (int) substr($lastTicket->ticket_code, -4) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . '-' . $newNumber;
    }
}
