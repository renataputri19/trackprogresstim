<?php

namespace App\Http\Controllers\NewHomepage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TutorialController extends Controller
{
    public function index()
    {
        // Fetch distinct categories
        $categories = DB::table('tutorials')
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->prepend('Semua');

        // Fetch all tutorials
        $tutorials = DB::table('tutorials')
            ->select('id', 'title', 'description', 'duration', 'thumbnail', 'category', 'slug')
            ->get()
            ->toArray();

        return view('new-homepage.tutorials', compact('tutorials', 'categories'));
    }

    public function show($slug)
    {
        // Fetch the tutorial by slug
        $tutorial = DB::table('tutorials')->where('slug', $slug)->first();

        if (!$tutorial) {
            abort(404, 'Tutorial tidak ditemukan');
        }

        // Decode chapters if present
        $tutorial->chapters = $tutorial->chapters ? json_decode($tutorial->chapters, true) : [];

        return view('new-homepage.tutorial-detail', compact('tutorial'));
    }
}