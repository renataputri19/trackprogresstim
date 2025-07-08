<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $features = [
            [
                'id' => 'performance',
                'title' => 'Employee Performance Integration',
                'description' => 'A dynamic dashboard for tracking employee tasks, monitoring work progress, and visualizing performance metrics.',
                'icon' => 'bar-chart-3',
                'image' => 'images/features/performance-dashboard.jpg',
                'benefits' => [
                    'Real-time task tracking and progress monitoring',
                    'Percentage-based performance visualization',
                    'Customizable KPI dashboards for management',
                    'Automated performance reports and analytics'
                ]
            ],
            [
                'id' => 'knowledge',
                'title' => 'Knowledge Management System',
                'description' => 'Thorough documentation across all BPS Batam sectors, archiving activity records and databases for easy access.',
                'icon' => 'book-open',
                'image' => 'images/features/knowledge-management.jpg',
                'benefits' => [
                    'Centralized document repository with version control',
                    'Advanced search functionality across all documents',
                    'Secure access controls and permission management',
                    'Collaborative editing and annotation tools'
                ]
            ],
            [
                'id' => 'ipds',
                'title' => 'Halo IP System',
                'description' => 'IT customer service tool generating tickets for non-IT staff to report and resolve technical issues efficiently.',
                'icon' => 'ticket',
                'image' => 'images/features/halo-ipds.jpg',
                'benefits' => [
                    'Intuitive ticket creation for technical issues',
                    'Real-time status tracking and notifications',
                    'Prioritization and assignment system for IT staff',
                    'Knowledge base for common issues and solutions'
                ]
            ],
            [
                'id' => 'padamu',
                'title' => 'Padamu Negeri System',
                'description' => 'Centralizes documentation for bureaucratic reform, consolidating all related processes into a single platform.',
                'icon' => 'file-text',
                'image' => 'images/features/padamu-negeri.jpg',
                'benefits' => [
                    'Streamlined bureaucratic reform documentation',
                    'Automated workflow for approval processes',
                    'Compliance tracking and reporting tools',
                    'Historical record of all reform initiatives'
                ]
            ],
            [
                'id' => 'vhts',
                'title' => 'VHTS Validation System',
                'description' => 'Supports employees in validating Hotel Occupancy Rate Survey results, streamlining data verification processes.',
                'icon' => 'database',
                'image' => 'images/features/vhts-validation.jpg',
                'benefits' => [
                    'Automated data validation and error detection',
                    'Statistical analysis and trend visualization',
                    'Collaborative review and approval workflow',
                    'Comprehensive reporting and export options'
                ]
            ],
            [
                'id' => 'data-kita',
                'title' => 'Data Kita System',
                'description' => 'A public-facing platform providing BPS data access to other government agencies, organizations, and citizens.',
                'icon' => 'share-2',
                'image' => 'images/features/data-kita.jpg',
                'benefits' => [
                    'Simplified access to public statistical data',
                    'Interactive data visualization and exploration tools',
                    'Data export in multiple formats (CSV, Excel, PDF)',
                    'API access for developers and third-party applications'
                ]
            ],
            [
                'id' => 'iak',
                'title' => 'Integration Administrasi Keuangan (IAK)',
                'description' => 'A streamlined solution for managing financial and administrative tasks for large-scale BPS Batam projects.',
                'icon' => 'dollar-sign',
                'image' => 'images/features/iak-system.jpg',
                'benefits' => [
                    'Project budgeting and financial planning tools',
                    'Participant documentation and contract management',
                    'Real-time tracking of survey paper status',
                    'Consolidated financial records and audit-ready reports'
                ]
            ]
        ];

        $benefits = [
            [
                'title' => 'Streamlined Operations',
                'description' => 'Optimize workflows and reduce administrative overhead through integrated systems and automated processes',
                'icon' => 'check-circle'
            ],
            [
                'title' => 'Enhanced Productivity',
                'description' => 'Track and improve employee performance with clear metrics, visualization tools, and actionable insights',
                'icon' => 'check-circle'
            ],
            [
                'title' => 'Centralized Knowledge',
                'description' => 'Access all organizational documentation and resources in one secure location with powerful search capabilities',
                'icon' => 'check-circle'
            ],
            [
                'title' => 'Efficient Issue Resolution',
                'description' => 'Quickly report and resolve technical problems through the integrated ticketing system with prioritization',
                'icon' => 'check-circle'
            ],
            [
                'title' => 'Data-Driven Decision Making',
                'description' => 'Make informed decisions based on comprehensive data, performance insights, and trend analysis',
                'icon' => 'check-circle'
            ],
            [
                'title' => 'Simplified Compliance',
                'description' => 'Streamline bureaucratic reform processes with consolidated documentation and automated workflows',
                'icon' => 'check-circle'
            ]
        ];

        $stats = [
            [
                'value' => '100%',
                'label' => 'Integration'
            ],
            [
                'value' => '5+',
                'label' => 'Core Systems'
            ],
            [
                'value' => '30%',
                'label' => 'Efficiency Boost'
            ],
            [
                'value' => '24/7',
                'label' => 'Availability'
            ]
        ];

        $highlightSystems = [
            [
                'id' => 'ipds',
                'title' => 'Halo IP System',
                'subtitle' => 'IT Support & Ticketing',
                'description' => 'IT customer service tool generating tickets for non-IT staff to report and resolve technical issues efficiently. Halo IP streamlines the entire support process from issue reporting to resolution tracking.',
                'icon' => 'ticket',
                'color' => 'blue',
                'image' => 'images/highlights/halo-ipds.jpg',
                'features' => [
                    'Intuitive Ticketing',
                    'Real-time Tracking',
                    'Priority Management',
                    'Knowledge Base'
                ]
            ],
            [
                'id' => 'iak',
                'title' => 'IAK System',
                'subtitle' => 'Integrated Administration & Finance',
                'description' => 'The Integration Administrasi Keuangan (IAK) System streamlines financial and administrative tasks for large-scale BPS Batam projects, supporting the entire project lifecycle from setup to reconciliation.',
                'icon' => 'dollar-sign',
                'color' => 'emerald',
                'image' => 'images/highlights/iak-system.jpg',
                'features' => [
                    'Project Budgeting',
                    'Contract Management',
                    'Survey Tracking',
                    'Financial Reporting'
                ]
            ]
        ];

        $workflowSteps = [
            [
                'title' => 'Unified Access',
                'description' => 'Single sign-on provides access to all integrated systems, eliminating the need for multiple logins',
                'icon' => 'users'
            ],
            [
                'title' => 'Real-time Updates',
                'description' => 'All data synchronizes in real-time across systems, ensuring everyone works with the latest information',
                'icon' => 'clock'
            ],
            [
                'title' => 'Actionable Insights',
                'description' => 'Advanced analytics provide meaningful insights to drive better decision-making and improve performance',
                'icon' => 'trending-up'
            ]
        ];

        return view('welcome', compact(
            'features',
            'benefits',
            'stats',
            'highlightSystems',
            'workflowSteps'
        ));
    }
}
