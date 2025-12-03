<?php

namespace App\Http\Controllers;

use App\Models\Ticket;

class PublicViewController extends Controller
{
    /**
     * View ticket by token (legacy method for backward compatibility)
     */
    public function viewTicket($token)
    {
        $ticket = Ticket::where('public_token', $token)->firstOrFail();

        return view('public.unified-view', compact('ticket'));
    }

    /**
     * View map request by token (legacy method for backward compatibility)
     */
    public function viewMapRequest($token)
    {
        $ticket = Ticket::where('public_token', $token)
            ->where('service_type', 'map_request')
            ->firstOrFail();

        return view('public.unified-view', compact('ticket'));
    }

    /**
     * Unified view by token - handles all ticket categories dynamically
     */
    public function viewByToken($token)
    {
        $ticket = Ticket::where('public_token', $token)->firstOrFail();

        return view('public.unified-view', compact('ticket'));
    }
}
