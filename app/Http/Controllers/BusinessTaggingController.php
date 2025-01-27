<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        $businesses = Business::latest()->get();
        
        // Additional statistics could be added here if needed
        return view('business.list', compact('businesses'));
    }
}