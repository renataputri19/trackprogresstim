<?php

namespace App\Http\Controllers\NewHomepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function index()
    {
        $categories = [
            [
                'name' => 'Getting Started',
                'icon' => 'rocket',
                'documents' => [
                    [
                        'title' => 'Introduction to RENTAK',
                        'description' => 'Overview of the RENTAK super app and its core features.',
                        'url' => '/documentation/introduction',
                    ],
                    [
                        'title' => 'System Requirements',
                        'description' => 'Hardware and software requirements for optimal RENTAK performance.',
                        'url' => '/documentation/system-requirements',
                    ],
                    [
                        'title' => 'User Registration',
                        'description' => 'Step-by-step guide to registering and setting up your RENTAK account.',
                        'url' => '/documentation/user-registration',
                    ],
                ],
            ],
            [
                'name' => 'Core Systems',
                'icon' => 'layout-dashboard',
                'documents' => [
                    [
                        'title' => 'Performance Tracking',
                        'description' => 'Comprehensive guide to the employee performance tracking system.',
                        'url' => '/documentation/performance-tracking',
                    ],
                    [
                        'title' => 'Knowledge Management',
                        'description' => 'How to use the knowledge repository and documentation features.',
                        'url' => '/documentation/knowledge-management',
                    ],
                    [
                        'title' => 'Halo IP',
                        'description' => 'Complete documentation for the IT support ticketing system.',
                        'url' => '/documentation/halo-ip',
                    ],
                    [
                        'title' => 'Padamu Negeri',
                        'description' => 'Guide to bureaucratic reform documentation management.',
                        'url' => '/documentation/padamu-negeri',
                    ],
                    [
                        'title' => 'VHTS Validation',
                        'description' => 'Detailed instructions for Hotel Occupancy Rate Survey validation.',
                        'url' => '/documentation/vhts-validation',
                    ],
                ],
            ],
            [
                'name' => 'External Systems',
                'icon' => 'share-2',
                'documents' => [
                    [
                        'title' => 'Data Kita Platform',
                        'description' => 'Documentation for the public-facing data access platform.',
                        'url' => '/documentation/data-kita',
                    ],
                    [
                        'title' => 'API Documentation',
                        'description' => 'Technical documentation for developers integrating with Data Kita.',
                        'url' => '/documentation/api-docs',
                    ],
                ],
            ],
            [
                'name' => 'Financial Management',
                'icon' => 'dollar-sign',
                'documents' => [
                    [
                        'title' => 'IAK System Overview',
                        'description' => 'Introduction to the Integrated Administration and Finance system.',
                        'url' => '/documentation/iak-overview',
                    ],
                    [
                        'title' => 'Project Budgeting',
                        'description' => 'How to create and manage project budgets in IAK.',
                        'url' => '/documentation/project-budgeting',
                    ],
                    [
                        'title' => 'Survey Paper Tracking',
                        'description' => 'Guide to tracking survey papers throughout the project lifecycle.',
                        'url' => '/documentation/survey-tracking',
                    ],
                    [
                        'title' => 'Financial Reporting',
                        'description' => 'Instructions for generating and interpreting financial reports.',
                        'url' => '/documentation/financial-reporting',
                    ],
                ],
            ],
            [
                'name' => 'Administration',
                'icon' => 'settings',
                'documents' => [
                    [
                        'title' => 'User Management',
                        'description' => 'Managing user accounts, permissions, and access control.',
                        'url' => '/documentation/user-management',
                    ],
                    [
                        'title' => 'System Configuration',
                        'description' => 'Advanced configuration options for system administrators.',
                        'url' => '/documentation/system-configuration',
                    ],
                    [
                        'title' => 'Data Backup & Recovery',
                        'description' => 'Procedures for backing up and recovering system data.',
                        'url' => '/documentation/backup-recovery',
                    ],
                ],
            ],
        ];

        $recentUpdates = [
            [
                'title' => 'Data Kita API v2.0',
                'date' => '2024-04-15',
                'description' => 'Updated API documentation with new endpoints and improved authentication.',
            ],
            [
                'title' => 'IAK Financial Reporting',
                'date' => '2024-04-10',
                'description' => 'Added new section on generating custom financial reports.',
            ],
            [
                'title' => 'Performance Dashboard Guide',
                'date' => '2024-04-05',
                'description' => 'Completely revised with new screenshots and workflow examples.',
            ],
        ];

        return view('new-homepage.documentation', compact('categories', 'recentUpdates'));
    }

    public function show($slug)
    {
        // In a real application, you would fetch the documentation from the database
        // For now, we'll just return a placeholder view
        return view('new-homepage.documentation-detail', ['slug' => $slug]);
    }
}
