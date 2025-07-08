<?php

namespace App\Http\Controllers\NewHomepage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DocumentationController extends Controller
{
    public function index()
    {
        // Fetch distinct categories with their icons
        $categories = DB::table('documentation')
            ->select('category', 'icon')
            ->distinct()
            ->get()
            ->map(function ($category) {
                // Fetch documents for each category
                $category->documents = DB::table('documentation')
                    ->where('category', $category->category)
                    ->select('title', 'description', 'slug')
                    ->get()
                    ->toArray();
                return $category;
            });

        // Hardcoded recent updates (can be moved to a separate table if needed)
        $recentUpdates = [
            [
                'title' => 'Integration Administrasi Keuangan v1.2',
                'date' => '10 Mei 2025',
                'description' => 'Menambahkan fitur pelacakan dokumen survei real-time dan templat laporan audit.',
            ],
            [
                'title' => 'Panduan VHTS Validation System',
                'date' => '5 Mei 2025',
                'description' => 'Diperbarui dengan contoh validasi data survei hotel terbaru.',
            ],
            [
                'title' => 'Halo IPDS System',
                'date' => '1 Mei 2025',
                'description' => 'Menambahkan panduan prioritas tiket dan analitik performa tim IT.',
            ],
        ];

        return view('new-homepage.documentation', compact('categories', 'recentUpdates'));
    }

    public function show($slug)
    {
        // Fetch the document by slug
        $document = DB::table('documentation')->where('slug', $slug)->first();

        if (!$document) {
            abort(404, 'Dokumentasi tidak ditemukan');
        }

        return view('new-homepage.documentation-detail', compact('document'));
    }
}