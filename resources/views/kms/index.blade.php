@extends('layouts.main')

<head>

    <style>
        /* Custom CSS for enhanced UI */
        .kms-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 2rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .kms-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .kms-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .kms-card .card-body {
            padding: 1.5rem;
        }

        .kms-card .card-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .kms-card .card-text {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-outline-secondary {
            padding: 0.35rem 0.75rem;
            border-radius: 0.5rem;
        }

        .notification {
            position: fixed;
            top: 1rem;
            right: 1rem;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transform: translateX(150%);
            transition: transform 0.3s ease-in-out;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification-success {
            border-left: 4px solid #28a745;
        }

        .notification-error {
            border-left: 4px solid #dc3545;
        }

        /* Form styles */
        .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
        }

        .breadcrumb {
            background: transparent;
            padding: 1rem 0;
        }

        .breadcrumb-item a {
            color: #6c757d;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #2c3e50;
            font-weight: 500;
        }

        /* Delete button styling */
        .btn-delete {
            background-color: #fff;
            color: #dc3545;
            border: 1px solid #dc3545;
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .btn-delete:hover {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>



@section('content')
    <div class="container">
        <div class="kms-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0">Knowledge Management System</h1>
                <a href="{{ route('kms.divisions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i> Create New Division
                </a>
            </div>
        </div>

        <div class="row">
            @foreach ($divisions as $division)
                <div class="col-md-4 mb-4">
                    <div class="card kms-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0">{{ $division->name }}</h5>
                                <a href="{{ route('kms.divisions.edit', $division->slug) }}"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                            <p class="card-text">{{ $division->description }}</p>
                            <a href="{{ route('kms.division', $division->slug) }}" class="btn btn-primary w-100">
                                <i class="fas fa-tasks mr-2"></i> View Activities
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>



@endsection
