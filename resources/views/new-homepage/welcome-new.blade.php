@extends('new-homepage.layouts.app')

@section('title', 'RENTAK - Reformasi dan Integrasi Kinerja')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/new-homepage/homepage-new.css') }}">
@endsection

@section('content')
<div class="rentak-homepage">
    <!-- Hero Section -->
    <section class="rentak-section" style="background: linear-gradient(to bottom, #f0fdfa, #ffffff);">
        <div class="rentak-container">
            <h1 class="rentak-title">All-in-One Super App</h1>
            <p class="rentak-subtitle">
                RENTAK serves as a centralized hub, offering a suite of powerful features tailored to enhance efficiency
            </p>
            
            <!-- Features Swiper -->
            <div class="rentak-swiper-container" id="features-swiper">
                <div class="rentak-swiper-button rentak-swiper-button-prev">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                </div>
                
                <div class="rentak-swiper-wrapper">
                    <!-- Slide 1 -->
                    <div class="rentak-swiper-slide">
                        <div class="rentak-card">
                            <div class="rentak-card-header">
                                <h3 class="rentak-card-title">Employee Performance Integration</h3>
                            </div>
                            <div class="rentak-card-body">
                                <p class="rentak-card-description">
                                    A dynamic dashboard for tracking employee tasks, monitoring work progress, and visualizing performance metrics.
                                </p>
                                <ul class="rentak-feature-list">
                                    <li class="rentak-feature-item">
                                        <span class="rentak-feature-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                        </span>
                                        <span class="rentak-feature-text">Real-time task tracking and progress monitoring</span>
                                    </li>
                                    <li class="rentak-feature-item">
                                        <span class="rentak-feature-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                        </span>
                                        <span class="rentak-feature-text">Percentage-based performance visualization</span>
                                    </li>
                                    <li class="rentak-feature-item">
                                        <span class="rentak-feature-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                        </span>
                                        <span class="rentak-feature-text">Customizable KPI dashboards for management</span>
                                    </li>
                                    <li class="rentak-feature-item">
                                        <span class="rentak-feature-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                        </span>
                                        <span class="rentak-feature-text">Automated performance reports and analytics</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="rentak-card-footer">
                                <a href="#" class="rentak-link">
                                    Learn more
                                    <span class="rentak-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 12h14"/>
                                            <path d="m12 5 7 7-7 7"/>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Slide 2 -->
                    <div class="rentak-swiper-slide">
                        <div class="rentak-card">
                            <div class="rentak-card-header">
                                <h3 class="rentak-card-title">Knowledge Management System</h3>
                            </div>
                            <div class="rentak-card-body">
                                <p class="rentak-card-description">
                                    A centralized repository for organizational knowledge and documentation to facilitate knowledge sharing and preservation.
                                </p>
                                <ul class="rentak-feature-list">
                                    <li class="rentak-feature-item">
                                        <span class="rentak-feature-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                        </span>
                                        <span class="rentak-feature-text">Centralized knowledge repository</span>
                                    </li>
                                    <li class="rentak-feature-item">
                                        <span class="rentak-feature-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                        </span>
                                        <span class="rentak-feature-text">Searchable documentation and resources</span>
                                    </li>
                                    <li class="rentak-feature-item">
                                        <span class="rentak-feature-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                        </span>
                                        <span class="rentak-feature-text">Collaborative knowledge sharing</span>
                                    </li>
                                    <li class="rentak-feature-item">
                                        <span class="rentak-feature-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                        </span>
                                        <span class="rentak-feature-text">Preservation of institutional knowledge</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="rentak-card-footer">
                                <a href="#" class="rentak-link">
                                    Learn more
                                    <span class="rentak-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 12h14"/>
                                            <path d="m12 5 7 7-7 7"/>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Slide 3 -->
                    <div class="rentak-swiper-slide">
                        <div class="rentak-card">
                            <div class="rentak-card-header">
                                <h3 class="rentak-card-title">IT Support Ticketing</h3>
                            </div>
                            <div class="rentak-card-body">
                                <p class="rentak-card-description">
                                    A streamlined system for reporting, tracking, and resolving IT issues, ensuring prompt and efficient technical support.
                                </p>
                                <ul class="rentak-feature-list">
                                    <li class="rentak-feature-item">
                                        <span class="rentak-feature-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                        </span>
                                        <span class="rentak-feature-text">Automated ticket assignment and prioritization</span>
                                    </li>
                                    <li class="rentak-feature-item">
                                        <span class="rentak-feature-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                        </span>
                                        <span class="rentak-feature-text">Real-time status updates and notifications</span>
                                    </li>
                                    <li class="rentak-feature-item">
                                        <span class="rentak-feature-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                        </span>
                                        <span class="rentak-feature-text">Comprehensive issue tracking and resolution</span>
                                    </li>
                                    <li class="rentak-feature-item">
                                        <span class="rentak-feature-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                        </span>
                                        <span class="rentak-feature-text">Performance analytics for IT support team</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="rentak-card-footer">
                                <a href="#" class="rentak-link">
                                    Learn more
                                    <span class="rentak-link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 12h14"/>
                                            <path d="m12 5 7 7-7 7"/>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="rentak-swiper-button rentak-swiper-button-next">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </div>
                
                <div class="rentak-swiper-pagination"></div>
            </div>
            
            <div class="rentak-text-center rentak-mt-8">
                <a href="{{ route('login') }}" class="rentak-button">
                    Access RENTAK
                    <span class="rentak-button-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/>
                            <path d="m12 5 7 7-7 7"/>
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/new-homepage/homepage-new.js') }}"></script>
@endsection
