<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class HaloIPController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['requestor', 'itStaff']);

        // Filter by category
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        // Filter by month
        if ($month = $request->input('month')) {
            $query->whereMonth('created_at', $month);
        }

        // Filter by IT staff
        if ($itStaff = $request->input('it_staff')) {
            $query->where('it_staff_id', $itStaff);
        }

        // Filter by status
        $statuses = Arr::wrap($request->input('status', []));
        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        // Filter by map type (for Peta Cetak category)
        if ($mapType = $request->input('map_type')) {
            $query->where('map_type', $mapType);
        }

        // Filter by district (for Peta Cetak category)
        if ($kdkec = $request->input('kdkec')) {
            $query->where('kdkec', $kdkec);
        }

        // Responsive pagination: 5 items for mobile/tablet, 10 for laptop/desktop
        // Detect device size from user agent or use a default
        $userAgent = $request->header('User-Agent');
        $isMobileOrTablet = preg_match('/(android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini|mobile|tablet)/i', $userAgent);

        // Also check screen width if provided via query parameter (for JavaScript-based detection)
        $screenWidth = $request->input('screen_width');
        if ($screenWidth !== null) {
            $isMobileOrTablet = $screenWidth < 1024; // Less than 1024px is considered mobile/tablet
        }

        $perPage = $isMobileOrTablet ? 5 : 10;

        $tickets = $query->latest()->paginate($perPage);
        $itStaffList = User::where('is_it_staff', true)->get();
        $categories = Ticket::getCategories();

        return view('haloip.index', compact('tickets', 'itStaffList', 'categories'));
    }

    public function create()
    {
        $categories = Ticket::getCategories();
        $ruanganList = [
            'Ruang Mencaras', 'Ruang Ranoh', 'Ruang Nirup', 'Ruang Abang',
            'Ruang Galang', 'Ruang Kepala', 'Ruang PPID & PST',
        ];
        $mapTypes = [
            'kecamatan' => 'Peta Kecamatan',
            'kelurahan' => 'Peta Kelurahan'
        ];
        $districts = LocationService::getDistrictsForDropdown();

        return view('haloip.create', compact('categories', 'ruanganList', 'mapTypes', 'districts'));
    }

    public function store(Request $request)
    {
        // Base validation rules
        $rules = [
            'category' => 'required|in:' . implode(',', array_keys(Ticket::getCategories())),
            'title' => 'required|string|max:255',
            'description' => 'required',
        ];

        // Add ruangan validation for non-Peta Cetak categories
        if ($request->category !== 'Peta Cetak') {
            $rules['ruangan'] = 'required|in:Ruang Mencaras,Ruang Ranoh,Ruang Nirup,Ruang Abang,Ruang Galang,Ruang Kepala,Ruang PPID & PST';
            $rules['requestor_photo'] = 'required|image|max:2048';
        } else {
            // Peta Cetak specific validation
            $rules['requestor_photo'] = 'nullable|image|max:2048';
            $rules['map_type'] = 'required|in:kecamatan,kelurahan';
            $rules['kdkec'] = 'required|string';
            $rules['kddesa'] = 'nullable|string';
        }

        $request->validate($rules);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('requestor_photo')) {
            $photoPath = $request->file('requestor_photo')->store('tickets/requestors', 'public');
        }

        // Prepare ticket data
        $ticketData = [
            'ticket_code' => Ticket::generateTicketCode($request->category),
            'user_id' => Auth::id(),
            'category' => $request->category,
            'title' => $request->title,
            'description' => $request->description,
            'requestor_photo' => $photoPath,
            'public_token' => Ticket::generatePublicToken(),
        ];

        // Set service_type for backward compatibility
        $ticketData['service_type'] = ($request->category === 'Peta Cetak') ? 'map_request' : 'ticket';

        // Add category-specific fields
        if ($request->category === 'Peta Cetak') {
            $ticketData['ruangan'] = 'Peta Cetak';
            $ticketData['map_type'] = $request->map_type;
            $ticketData['kdkec'] = $request->kdkec;
            $ticketData['nmkec'] = LocationService::getDistrictName($request->kdkec);
            
            if ($request->kddesa) {
                $ticketData['kddesa'] = $request->kddesa;
                $ticketData['nmdesa'] = LocationService::getVillageName($request->kdkec, $request->kddesa);
            }
        } else {
            $ticketData['ruangan'] = $request->ruangan;
        }

        Ticket::create($ticketData);

        return redirect()->route('haloip.index', ['ticket_success' => 'true'])->with('success', 'Tiket berhasil diajukan!');
    }

    public function manage(Request $request)
    {
        // Get the active tab (default to 'my_tickets')
        $activeTab = $request->input('tab', 'my_tickets');

        $query = Ticket::query();

        // Apply tab-based filtering
        if ($activeTab === 'my_tickets') {
            // Show only tickets assigned to the current IT staff
            $query->where('it_staff_id', Auth::id());
        } elseif ($activeTab === 'unassigned') {
            // Show only unassigned tickets
            $query->whereNull('it_staff_id');
        }

        // Filter by category
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        // Filter by month
        if ($month = $request->input('month')) {
            $query->whereMonth('created_at', $month);
        }

        // Filter by status
        if ($statuses = $request->input('status')) {
            $query->whereIn('status', $statuses);
        }

        // Responsive pagination: 5 items for mobile/tablet, 10 for laptop/desktop
        $userAgent = $request->header('User-Agent');
        $isMobileOrTablet = preg_match('/(android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini|mobile|tablet)/i', $userAgent);

        // Also check screen width if provided via query parameter
        $screenWidth = $request->input('screen_width');
        if ($screenWidth !== null) {
            $isMobileOrTablet = $screenWidth < 1024;
        }

        $perPage = $isMobileOrTablet ? 5 : 10;

        $tickets = $query->latest()->paginate($perPage)->appends($request->except('page'));
        $categories = Ticket::getCategories();

        // Calculate IT staff statistics for dashboard summary
        // Only get assigned tickets with status 'pending' or 'in_progress'
        $itStaffStats = Ticket::whereNotNull('it_staff_id')
            ->whereIn('status', ['pending', 'in_progress'])
            ->with('itStaff')
            ->get()
            ->groupBy('it_staff_id')
            ->map(function ($tickets, $staffId) {
                $staff = $tickets->first()->itStaff;
                return [
                    'staff_id' => (int) $staffId,
                    'name' => $staff ? $staff->name : 'Unknown',
                    'total' => $tickets->count(),
                    'pending' => $tickets->where('status', 'pending')->count(),
                    'in_progress' => $tickets->where('status', 'in_progress')->count(),
                    // Provide ticket details for expandable breakdown rows
                    'tickets' => $tickets->sortByDesc('created_at')->values(),
                ];
            })
            ->sortByDesc('total')
            ->values();

        // Calculate summary totals
        $summaryTotals = [
            'total_active' => $itStaffStats->sum('total'),
            'total_pending' => $itStaffStats->sum('pending'),
            'total_in_progress' => $itStaffStats->sum('in_progress'),
            'staff_count' => $itStaffStats->count(),
        ];

        return view('haloip.manage', compact('tickets', 'categories', 'activeTab', 'itStaffStats', 'summaryTotals'));
    }

    public function show(Ticket $ticket)
    {
        // Check if IT staff has access to this ticket
        if ($ticket->it_staff_id !== Auth::id() && $ticket->it_staff_id !== null) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        // Get IT staff list for assignment dropdown
        $itStaffList = User::where('is_it_staff', true)->get();

        return view('haloip.show', compact('ticket', 'itStaffList'));
    }

    public function assign(Ticket $ticket)
    {
        // Assignment page is accessible to all IT staff
        // Get IT staff list for assignment dropdown
        $itStaffList = User::where('is_it_staff', true)->get();

        return view('haloip.assign', compact('ticket', 'itStaffList'));
    }

    public function storeAssignment(Request $request, Ticket $ticket)
    {
        // Build validation rules
        $rules = [
            'it_staff_id' => 'required|exists:users,id',
        ];

        // Phone number is optional but should be validated if provided
        if ($request->filled('it_staff_phone')) {
            $rules['it_staff_phone'] = ['nullable', 'string', 'regex:/^(\+62|62|08)[0-9]{8,13}$/'];
        }

        $request->validate($rules, [
            'it_staff_phone.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format +62xxx, 62xxx, atau 08xxx.',
        ]);

        $ticket->update([
            'it_staff_id' => $request->it_staff_id,
        ]);

        // Get assigned IT staff for WhatsApp notification
        $assignedStaff = User::find($request->it_staff_id);
        $whatsappUrl = null;

        // Save/update phone number for the IT staff if provided
        if ($request->filled('it_staff_phone') && $assignedStaff) {
            $normalizedPhone = $this->normalizePhoneNumber($request->it_staff_phone);
            $assignedStaff->update(['phone_number' => $normalizedPhone]);
        }

        // Get the phone number (either from form input or existing database record)
        $phoneNumber = null;
        if ($request->filled('it_staff_phone')) {
            $phoneNumber = $this->normalizePhoneNumber($request->it_staff_phone);
        } elseif ($assignedStaff && $assignedStaff->phone_number) {
            $phoneNumber = $assignedStaff->phone_number;
        }

        if ($phoneNumber) {
            // Format phone number (remove + sign for wa.me link)
            $waNumber = ltrim($phoneNumber, '+');

            // Build WhatsApp message
            $ticketType = $ticket->category === 'Peta Cetak' ? 'Permintaan Peta' : 'Tiket';
            $message = "🎫 *PENUGASAN {$ticketType} BARU*\n\n";
            $message .= "Halo {$assignedStaff->name},\n\n";
            $message .= "Anda telah ditugaskan untuk menangani {$ticketType} berikut:\n\n";
            $message .= "📋 *Kode*: {$ticket->ticket_code}\n";
            $message .= "🏷️ *Kategori*: {$ticket->category}\n";
            $message .= "📝 *Judul*: {$ticket->title}\n";
            $message .= "📄 *Deskripsi*: {$ticket->description}\n";
            $message .= "👤 *Pengaju*: " . ($ticket->requestor->name ?? 'Unknown') . "\n";
            $message .= "🏢 *Ruangan*: {$ticket->ruangan}\n";
            $message .= "📅 *Tanggal*: " . $ticket->created_at->format('d/m/Y H:i') . "\n\n";
            $message .= "Silakan segera proses {$ticketType} ini.\n\n";
            $message .= "🔗 *Link HaloIP*: " . route('haloip.editStatus', $ticket->id) . "\n\n";
            $message .= "Terima kasih! 🙏";

            $whatsappUrl = "https://wa.me/{$waNumber}?text=" . urlencode($message);
        }

        return redirect()->route('haloip.manage')
            ->with('success', 'Petugas IT berhasil ditugaskan!')
            ->with('whatsapp_url', $whatsappUrl)
            ->with('assigned_staff_name', $assignedStaff->name ?? '');
    }

    public function editStatus(Ticket $ticket)
    {
        // Only the assigned IT staff can access this page
        if ((int) $ticket->it_staff_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized: Only the assigned IT staff can update this ticket.');
        }

        return view('haloip.update-status', compact('ticket'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        // Only the assigned IT staff can update the status
        if ((int) $ticket->it_staff_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized: Only the assigned IT staff can update this ticket.');
        }

        // Determine if IT photo is required based on category and status
        $itPhotoRequired = $ticket->isItPhotoRequired($request->status);

        // Build validation rules
        $rules = [
            'it_photo' => $itPhotoRequired ? 'required|image|max:2048' : 'nullable|image|max:2048',
            'status' => 'required|in:pending,in_progress,completed',
        ];

        // Phone number is required when status is completed
        if ($request->status === 'completed') {
            // Use array syntax to avoid conflict between Laravel's pipe separator and regex OR operator
            $rules['requestor_phone'] = ['required', 'string', 'regex:/^(\+62|62|08)[0-9]{8,13}$/'];
        }

        $request->validate($rules, [
            'requestor_phone.required' => 'Nomor WhatsApp pengaju wajib diisi saat status Selesai.',
            'requestor_phone.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format +62xxx, 62xxx, atau 08xxx.',
        ]);

        $data = ['status' => $request->status];

        if ($request->hasFile('it_photo')) {
            $data['it_photo'] = $request->file('it_photo')->store('tickets/it_staff', 'public');
        }

        if ($request->status === 'completed' && !$ticket->done_at) {
            $data['done_at'] = now();
        } elseif ($request->status !== 'completed' && $ticket->done_at) {
            $data['done_at'] = null;
        }

        $ticket->update($data);

        // Handle WhatsApp notification for completed status
        $whatsappUrl = null;
        $requestorName = $ticket->requestor->name ?? 'Pengaju';

        if ($request->status === 'completed') {
            $waNumber = null;

            // Use provided phone when available and save it
            if ($request->filled('requestor_phone')) {
                $normalizedPhone = $this->normalizePhoneNumber($request->requestor_phone);

                $requestor = $ticket->requestor;
                if ($requestor) {
                    $requestor->update(['phone_number' => $normalizedPhone]);
                    $requestorName = $requestor->name;
                }

                $waNumber = ltrim($normalizedPhone, '+');
            } elseif ($ticket->requestor && $ticket->requestor->phone_number) {
                // Fallback to stored phone number if present
                $waNumber = ltrim($ticket->requestor->phone_number, '+');
            }

            // Build WhatsApp message
            $ticketType = $ticket->category === 'Peta Cetak' ? 'Permintaan Peta' : 'Tiket';
            $publicLink = route('public.view', $ticket->public_token);

            $message = "✅ *{$ticketType} TELAH SELESAI*\n\n";
            $message .= "Halo {$requestorName},\n\n";
            $message .= "{$ticketType} Anda telah selesai dikerjakan.\n\n";
            $message .= "📋 *Kode*: {$ticket->ticket_code}\n";
            $message .= "🏷️ *Kategori*: {$ticket->category}\n";
            $message .= "📝 *Judul*: {$ticket->title}\n";
            $message .= "📅 *Selesai*: " . now()->format('d/m/Y H:i') . "\n\n";
            $message .= "🔗 *Lihat Detail*:\n{$publicLink}\n\n";
            $message .= "Terima kasih telah menggunakan layanan HaloIP! 🙏";

            // Always build a WhatsApp link: with number when available, otherwise generic share link
            if ($waNumber) {
                $whatsappUrl = "https://wa.me/{$waNumber}?text=" . urlencode($message);
            } else {
                $whatsappUrl = "https://wa.me/?text=" . urlencode($message);
            }
        }

        $successMessage = $ticket->category === 'Peta Cetak'
            ? 'Status permintaan peta berhasil diperbarui!'
            : 'Status tiket berhasil diperbarui!';

        return redirect()->route('haloip.manage')
            ->with('success', $successMessage)
            ->with('whatsapp_url_completion', $whatsappUrl)
            ->with('requestor_name', $requestorName);
    }

    /**
     * Normalize phone number to +62 format
     */
    private function normalizePhoneNumber(string $phone): string
    {
        // Remove any spaces, dashes, or dots
        $phone = preg_replace('/[\s\-\.]/', '', $phone);

        // Convert 08xxx to +628xxx
        if (str_starts_with($phone, '08')) {
            return '+62' . substr($phone, 1);
        }

        // Convert 628xxx to +628xxx
        if (str_starts_with($phone, '62')) {
            return '+' . $phone;
        }

        // Already in +62 format
        if (str_starts_with($phone, '+62')) {
            return $phone;
        }

        // Return as-is if format is unknown
        return $phone;
    }

    public function destroy(Ticket $ticket)
    {
        // Only IT staff can delete; if assigned, restrict to the assigned staff
        if (!Auth::user()->is_it_staff) {
            abort(403, 'Unauthorized: Only IT staff can delete tickets.');
        }

        if ($ticket->it_staff_id !== null && $ticket->it_staff_id !== Auth::id()) {
            abort(403, 'Unauthorized: Only the assigned IT staff can delete this ticket.');
        }

        $ticket->delete();

        return redirect()->route('haloip.manage')->with('success', 'Tiket berhasil dihapus!');
    }

    public function update(Request $request, Ticket $ticket)
    {
        // Handle assignment-only action
        if ($request->input('action') === 'assign_only') {
            $request->validate([
                'it_staff_id' => 'required|exists:users,id',
            ]);

            $ticket->update([
                'it_staff_id' => $request->it_staff_id,
            ]);

            return redirect()->route('haloip.manage')->with('success', 'Petugas IT berhasil ditugaskan!');
        }

        // Handle status update action
        // Determine if IT photo is required based on category and status
        $itPhotoRequired = $ticket->isItPhotoRequired($request->status);

        $request->validate([
            'it_photo' => $itPhotoRequired ? 'required|image|max:2048' : 'nullable|image|max:2048',
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

        return redirect()->route('haloip.manage')->with('success', 'Tiket berhasil diperbarui!');
    }

    public function pendingCount(Request $request)
    {
        if (!Auth::user()->is_it_staff) {
            abort(403, 'Unauthorized');
        }

        $pendingCount = Ticket::where('status', 'pending')->count();
        return response()->json(['pendingCount' => $pendingCount]);
    }

    public function getTickets(Request $request)
    {
        $query = Ticket::with(['requestor', 'itStaff']);

        // Filter by category
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        // Filter by month
        if ($month = $request->input('month')) {
            $query->whereMonth('created_at', $month);
        }

        // Filter by IT staff
        if ($itStaff = $request->input('it_staff')) {
            $query->where('it_staff_id', $itStaff);
        }

        // Filter by status
        $statuses = Arr::wrap($request->input('status', []));
        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        $tickets = $query->latest()->get();

        return response()->json($tickets);
    }

    public function getVillagesByDistrict(Request $request, $districtCode)
    {
        $villages = LocationService::getVillagesForDropdown($districtCode);
        return response()->json($villages);
    }
}

