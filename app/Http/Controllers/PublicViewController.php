<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class PublicViewController extends Controller
{
    public function viewTicket($token)
    {
        $ticket = Ticket::where('public_token', $token)->firstOrFail();
        
        return view('public.ticket', compact('ticket'));
    }

    public function viewMapRequest($token)
    {
        $mapRequest = Ticket::where('public_token', $token)
            ->where('service_type', 'map_request')
            ->firstOrFail();
        
        return view('public.map-request', compact('mapRequest'));
    }

    public function viewByToken($token)
    {
        $item = Ticket::where('public_token', $token)->firstOrFail();
        
        if ($item->service_type === 'map_request') {
            return view('public.map-request', ['mapRequest' => $item]);
        } else {
            return view('public.ticket', ['ticket' => $item]);
        }
    }
}
