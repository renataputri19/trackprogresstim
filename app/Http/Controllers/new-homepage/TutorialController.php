<?php

namespace App\Http\Controllers\NewHomepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TutorialController extends Controller
{
    public function index()
    {
        $tutorials = [
            [
                'id' => 1,
                'title' => 'Getting Started with RENTAK',
                'description' => 'Learn the basics of navigating and using the RENTAK super app.',
                'duration' => '5:30',
                'thumbnail' => 'images/tutorials/getting-started.jpg',
                'category' => 'Basics',
            ],
            [
                'id' => 2,
                'title' => 'Performance Tracking Dashboard',
                'description' => 'Discover how to use the performance tracking features to monitor tasks and progress.',
                'duration' => '8:45',
                'thumbnail' => 'images/tutorials/performance-tracking.jpg',
                'category' => 'Performance',
            ],
            [
                'id' => 3,
                'title' => 'Knowledge Management System',
                'description' => 'Learn how to access, search, and contribute to the knowledge management system.',
                'duration' => '7:15',
                'thumbnail' => 'images/tutorials/knowledge-management.jpg',
                'category' => 'Knowledge',
            ],
            [
                'id' => 4,
                'title' => 'Using the Halo IPDS System',
                'description' => 'Step-by-step guide on creating and tracking IT support tickets.',
                'duration' => '6:20',
                'thumbnail' => 'images/tutorials/halo-ipds.jpg',
                'category' => 'IPDS',
            ],
            [
                'id' => 5,
                'title' => 'Padamu Negeri Documentation',
                'description' => 'How to manage bureaucratic reform documentation efficiently.',
                'duration' => '9:10',
                'thumbnail' => 'images/tutorials/padamu-negeri.jpg',
                'category' => 'Padamu',
            ],
            [
                'id' => 6,
                'title' => 'VHTS Validation Workflow',
                'description' => 'Complete guide to validating Hotel Occupancy Rate Survey results.',
                'duration' => '10:30',
                'thumbnail' => 'images/tutorials/vhts-validation.jpg',
                'category' => 'VHTS',
            ],
            [
                'id' => 7,
                'title' => 'Accessing Data Kita',
                'description' => 'How to access and utilize public data through the Data Kita system.',
                'duration' => '5:45',
                'thumbnail' => 'images/tutorials/data-kita.jpg',
                'category' => 'Data Kita',
            ],
            [
                'id' => 8,
                'title' => 'IAK Financial Management',
                'description' => 'Managing project finances and administration through the IAK system.',
                'duration' => '12:15',
                'thumbnail' => 'images/tutorials/iak-financial.jpg',
                'category' => 'IAK',
            ],
        ];

        $categories = [
            'All',
            'Basics',
            'Performance',
            'Knowledge',
            'IPDS',
            'Padamu',
            'VHTS',
            'Data Kita',
            'IAK',
        ];

        return view('new-homepage.tutorials', compact('tutorials', 'categories'));
    }

    public function show($id)
    {
        // In a real application, you would fetch the tutorial from the database
        // For now, we'll just return a placeholder view
        return view('new-homepage.tutorial-detail', ['id' => $id]);
    }
}
