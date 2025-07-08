<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['requestor', 'itStaff'])->where('service_type', 'ticket');

        if ($month = $request->input('month')) {
            $query->whereMonth('created_at', $month);
        }

        if ($itStaff = $request->input('it_staff')) {
            $query->where('it_staff_id', $itStaff);
        }

        $statuses = Arr::wrap($request->input('status', []));
        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        $tickets = $query->latest()->paginate(10);
        $itStaffList = User::where('is_it_staff', true)->get();

        return view('haloip.tickets.index', compact('tickets', 'itStaffList'));
    }

    public function create()
    {
        $ruanganList = [
            'Ruang Mencaras', 'Ruang Ranoh', 'Ruang Nirup', 'Ruang Abang',
            'Ruang Galang', 'Ruang Kepala', 'Ruang PPID & PST',
        ];
        return view('haloip.tickets.create', compact('ruanganList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'requestor_photo' => 'required|image|max:2048',
            'ruangan' => 'required|in:Ruang Mencaras,Ruang Ranoh,Ruang Nirup,Ruang Abang,Ruang Galang,Ruang Kepala,Ruang PPID & PST',
        ]);

        $photoPath = $request->file('requestor_photo')->store('tickets/requestors', 'public');

        Ticket::create([
            'ticket_code' => Ticket::generateTicketCode('ticket'),
            'user_id' => Auth::id(),
            'ruangan' => $request->ruangan,
            'title' => $request->title,
            'description' => $request->description,
            'requestor_photo' => $photoPath,
            'service_type' => 'ticket',
            'public_token' => Ticket::generatePublicToken(),
        ]);

        return redirect('/haloIP/ticket?ticket_success=true')->with('success', 'Tiket berhasil diajukan!');
    }

    public function manage(Request $request)
    {
        $query = Ticket::where('it_staff_id', auth()->id())->where('service_type', 'ticket');

        if ($month = $request->input('month')) {
            $query->whereMonth('created_at', $month);
        }

        if ($statuses = $request->input('status')) {
            $query->whereIn('status', $statuses);
        }

        $tickets = $query->latest()->paginate(10);

        return view('tickets.manage', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->service_type !== 'ticket') {
            abort(404, 'Ticket not found.');
        }

        if ($ticket->it_staff_id !== Auth::id() && $ticket->it_staff_id !== null) {
            abort(403, 'Unauthorized access to this ticket.');
        }
        return view('tickets.show', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'it_photo' => 'nullable|image|max:2048',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $data = ['status' => $request->status];
        if ($request->hasFile('it_photo')) {
            $data['it_photo'] = $request->file('it_photo')->store('tickets/it_staff', 'public');
        }
        if (!$ticket->it_staff_id) {
            $data['it_staff_id'] = Auth::id();
        }
        if ($request->status === 'completed' && !$ticket->done_at) {
            $data['done_at'] = now();
        } elseif ($request->status !== 'completed' && $ticket->done_at) {
            $data['done_at'] = null;
        }

        $ticket->update($data);
        return redirect()->route('tickets.manage')->with('success', 'Tiket berhasil diperbarui!');
    }

    public function pendingCount(Request $request)
    {
        if (!Auth::user()->is_it_staff) {
            abort(403, 'Unauthorized');
        }
        $pendingCount = Ticket::where('status', 'pending')->where('service_type', 'ticket')->count();
        return response()->json(['pendingCount' => $pendingCount]);
    }

    public function getTickets(Request $request)
    {
        $query = Ticket::with(['requestor', 'itStaff'])->where('service_type', 'ticket');

        if ($month = $request->input('month')) {
            $query->whereMonth('created_at', $month);
        }

        if ($itStaff = $request->input('it_staff')) {
            $query->where('it_staff_id', $itStaff);
        }

        $statuses = Arr::wrap($request->input('status', []));
        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        $tickets = $query->latest()->get();

        return response()->json($tickets);
    }
}