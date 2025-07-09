<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class MapRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['requestor', 'itStaff'])->where('service_type', 'map_request');

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

        if ($mapType = $request->input('map_type')) {
            $query->where('map_type', $mapType);
        }

        if ($kdkec = $request->input('kdkec')) {
            $query->where('kdkec', $kdkec);
        }

        // Keep zone filter for backward compatibility
        if ($zone = $request->input('zone')) {
            $query->where('zone', $zone);
        }

        $mapRequests = $query->latest()->paginate(10);
        $itStaffList = User::where('is_it_staff', true)->get();

        return view('haloip.map-requests.index', compact('mapRequests', 'itStaffList'));
    }

    public function create()
    {
        $mapTypes = [
            'kecamatan' => 'Peta Kecamatan',
            'kelurahan' => 'Peta Kelurahan'
        ];

        $districts = LocationService::getDistrictsForDropdown();

        return view('haloip.map-requests.create', compact('mapTypes', 'districts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'requestor_photo' => 'nullable|image|max:2048',
            'map_type' => 'required|in:kecamatan,kelurahan',
            'kdkec' => 'required|string',
            'kddesa' => 'nullable|string',
        ]);

        $photoPath = null;
        if ($request->hasFile('requestor_photo')) {
            $photoPath = $request->file('requestor_photo')->store('map-requests/requestors', 'public');
        }

        // Get district and village names
        $nmkec = LocationService::getDistrictName($request->kdkec);
        $nmdesa = null;

        if ($request->kddesa) {
            $nmdesa = LocationService::getVillageName($request->kdkec, $request->kddesa);
        }

        Ticket::create([
            'ticket_code' => Ticket::generateTicketCode('map_request'),
            'user_id' => Auth::id(),
            'ruangan' => 'Map Request', // Default value for map requests
            'title' => $request->title,
            'description' => $request->description,
            'requestor_photo' => $photoPath,
            'service_type' => 'map_request',
            'map_type' => $request->map_type,
            'kdkec' => $request->kdkec,
            'nmkec' => $nmkec,
            'kddesa' => $request->kddesa,
            'nmdesa' => $nmdesa,
            'public_token' => Ticket::generatePublicToken(),
        ]);

        return redirect('/haloIP/map-request?request_success=true')->with('success', 'Permintaan peta berhasil diajukan!');
    }

    public function manage(Request $request)
    {
        $query = Ticket::where('it_staff_id', auth()->id())->where('service_type', 'map_request');

        if ($month = $request->input('month')) {
            $query->whereMonth('created_at', $month);
        }

        if ($statuses = $request->input('status')) {
            $query->whereIn('status', $statuses);
        }

        $mapRequests = $query->latest()->paginate(10);

        return view('map-requests.manage', compact('mapRequests'));
    }

    public function show(Ticket $mapRequest)
    {
        if ($mapRequest->service_type !== 'map_request') {
            abort(404, 'Map request not found.');
        }

        if ($mapRequest->it_staff_id !== Auth::id() && $mapRequest->it_staff_id !== null) {
            abort(403, 'Unauthorized access to this map request.');
        }

        return view('map-requests.show', compact('mapRequest'));
    }

    public function update(Request $request, Ticket $mapRequest)
    {
        if ($mapRequest->service_type !== 'map_request') {
            abort(404, 'Map request not found.');
        }

        $request->validate([
            'it_photo' => $mapRequest->isItPhotoRequired() ? 'required|image|max:2048' : 'nullable|image|max:2048',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $data = ['status' => $request->status];
        
        if ($request->hasFile('it_photo')) {
            $data['it_photo'] = $request->file('it_photo')->store('map-requests/it_staff', 'public');
        }
        
        if (!$mapRequest->it_staff_id) {
            $data['it_staff_id'] = Auth::id();
        }
        
        if ($request->status === 'completed' && !$mapRequest->done_at) {
            $data['done_at'] = now();
        } elseif ($request->status !== 'completed' && $mapRequest->done_at) {
            $data['done_at'] = null;
        }

        $mapRequest->update($data);
        
        return redirect()->route('map-requests.manage')->with('success', 'Permintaan peta berhasil diperbarui!');
    }

    public function pendingCount(Request $request)
    {
        if (!Auth::user()->is_it_staff) {
            abort(403, 'Unauthorized');
        }
        
        $pendingCount = Ticket::where('status', 'pending')->where('service_type', 'map_request')->count();
        return response()->json(['pendingCount' => $pendingCount]);
    }

    public function getMapRequests(Request $request)
    {
        $query = Ticket::with(['requestor', 'itStaff'])->where('service_type', 'map_request');

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

        if ($mapType = $request->input('map_type')) {
            $query->where('map_type', $mapType);
        }

        if ($kdkec = $request->input('kdkec')) {
            $query->where('kdkec', $kdkec);
        }

        // Keep zone filter for backward compatibility
        if ($zone = $request->input('zone')) {
            $query->where('zone', $zone);
        }

        $mapRequests = $query->latest()->get();

        return response()->json($mapRequests);
    }

    public function getVillagesByDistrict(Request $request, $districtCode)
    {
        $villages = LocationService::getVillagesForDropdown($districtCode);
        return response()->json($villages);
    }
}
