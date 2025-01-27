<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class BusinessTaggingController extends Controller
{
    public function index()
    {
        return view('business.tagging');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'deskripsi_usaha' => 'nullable|string',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric'
        ]);

        $business = Business::create($validated);

        return response()->json([
            'message' => 'Data usaha berhasil disimpan',
            'business' => $business
        ]);
    }

    public function list()
    {
        // Get all businesses for the map
        $allBusinesses = Business::all();
        
        // Get paginated businesses for the list
        $businesses = Business::latest()->paginate(10);
        
        $stats = [
            'total' => Business::count(),
            'weekly' => Business::where('created_at', '>=', now()->subDays(7))->count(),
            'daily' => Business::where('created_at', '>=', now()->subDay())->count()
        ];
        
        return view('business.list', compact('businesses', 'allBusinesses', 'stats'));
    }
}