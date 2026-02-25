@extends('new-homepage.layouts.app')

@section('title', 'Dashboard - RENTAK')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-teal-50">
    <!-- Dashboard Header -->
    <div class="relative overflow-hidden bg-gradient-to-r from-teal-600 via-emerald-500 to-teal-600 py-16">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">
                    Selamat Datang, {{ $user->name }}!
                </h1>
                <div class="mx-auto mt-6 max-w-2xl text-xl text-teal-100">
                    <p id="dynamic-quote" class="transition-opacity duration-500 ease-in-out">
                        Akses semua aplikasi dan layanan BPS melalui dashboard terintegrasi RENTAK
                    </p>
                </div>
                @if($userTim)
                <div class="mt-4">
                    <span class="inline-flex items-center rounded-full bg-white/20 px-4 py-2 text-sm font-medium text-white backdrop-blur">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="m22 21-3-3m0 0a5.5 5.5 0 1 0-7.8-7.8 5.5 5.5 0 0 0 7.8 7.8Z"/>
                        </svg>
                        {{ $userTim->name }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Dashboard Stats - Commented out as this is now part of Employee Performance Integration --}}
    {{--
    <div class="relative -mt-8 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Overall Progress -->
            <div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-lg ring-1 ring-gray-200/50 transition-all duration-300 hover:shadow-xl hover:ring-teal-500/20">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-r from-teal-500 to-emerald-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <path d="m9 11 3 3L22 4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Progres Keseluruhan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $overallProgress }}%</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="h-2 w-full rounded-full bg-gray-200">
                        <div class="h-2 rounded-full bg-gradient-to-r from-teal-500 to-emerald-500 transition-all duration-500" style="width: {{ $overallProgress }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Total Tasks -->
            <div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-lg ring-1 ring-gray-200/50 transition-all duration-300 hover:shadow-xl hover:ring-blue-500/20">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-r from-blue-500 to-indigo-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <path d="M14 2v6h6"/>
                                <path d="M16 13H8"/>
                                <path d="M16 17H8"/>
                                <path d="M10 9H8"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Total Tugas</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalTasks }}</p>
                    </div>
                </div>
            </div>

            <!-- Completed Tasks -->
            <div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-lg ring-1 ring-gray-200/50 transition-all duration-300 hover:shadow-xl hover:ring-green-500/20">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-r from-green-500 to-emerald-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 11l3 3L22 4"/>
                                <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.51 0 2.93.37 4.18 1.03"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Tugas Selesai</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $completedTasks }}</p>
                    </div>
                </div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-lg ring-1 ring-gray-200/50 transition-all duration-300 hover:shadow-xl hover:ring-orange-500/20">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-r from-orange-500 to-red-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 6v6l4 2"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Deadline Mendekati</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $upcomingDeadlines }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    --}}

    {{-- Dashboard Content - Commented out as this is now part of Employee Performance Integration --}}
    {{--
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Recent Tasks -->
            <div class="lg:col-span-2">
                <div class="overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-200/50">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">Tugas Terbaru</h3>
                        <p class="mt-1 text-sm text-gray-600">Daftar tugas yang sedang Anda kerjakan</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($recentTasks as $assignment)
                        <div class="p-6 transition-colors duration-200 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-base font-medium text-gray-900">{{ $assignment->task->name }}</h4>
                                    <p class="mt-1 text-sm text-gray-600">
                                        Target: {{ number_format($assignment->target) }} |
                                        Progress: {{ number_format($assignment->progress) }}
                                    </p>
                                    <div class="mt-2 flex items-center text-xs text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($assignment->task->end_date)->format('d M Y') }}
                                    </div>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    @php
                                        $percentage = $assignment->target > 0 ? round(($assignment->progress / $assignment->target) * 100, 1) : 0;
                                    @endphp
                                    <div class="flex items-center">
                                        <div class="mr-2 h-8 w-8 rounded-full bg-gray-200">
                                            <div class="flex h-full items-center justify-center">
                                                <span class="text-xs font-medium text-gray-700">{{ $percentage }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="h-2 w-full rounded-full bg-gray-200">
                                    <div class="h-2 rounded-full bg-gradient-to-r from-teal-500 to-emerald-500 transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-6 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-gray-400">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <path d="M14 2v6h6"/>
                                <path d="M16 13H8"/>
                                <path d="M16 17H8"/>
                                <path d="M10 9H8"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Belum ada tugas yang diberikan</p>
                        </div>
                        @endforelse
                    </div>
                    @if($recentTasks->count() > 0)
                    <div class="border-t border-gray-200 bg-gray-50 px-6 py-3">
                        <a href="{{ route(auth()->user()->is_admin ? 'admin.tasks.index' : 'user.tasks.index') }}"
                           class="text-sm font-medium text-teal-600 hover:text-teal-700 transition-colors duration-200">
                            Lihat semua tugas →
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="space-y-6">
                <!-- Quick Actions Card -->
                <div class="overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-200/50">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <a href="{{ route(auth()->user()->is_admin ? 'admin.tasks.index' : 'user.tasks.index') }}"
                           class="flex items-center rounded-lg border border-gray-200 p-4 transition-all duration-200 hover:border-teal-300 hover:bg-teal-50">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-teal-100 text-teal-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <path d="M14 2v6h6"/>
                                    <path d="M16 13H8"/>
                                    <path d="M16 17H8"/>
                                    <path d="M10 9H8"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Kelola Tugas</p>
                                <p class="text-xs text-gray-600">Lihat dan update progres tugas</p>
                            </div>
                        </a>

                        <a href="{{ route('settings') }}"
                           class="flex items-center rounded-lg border border-gray-200 p-4 transition-all duration-200 hover:border-blue-300 hover:bg-blue-50">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Pengaturan</p>
                                <p class="text-xs text-gray-600">Kelola profil dan akun</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Performance Summary -->
                <div class="overflow-hidden rounded-xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white shadow-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold">Ringkasan Kinerja</h3>
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-teal-100">Tingkat Penyelesaian</span>
                                <span class="text-sm font-medium">{{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-teal-100">Progres Rata-rata</span>
                                <span class="text-sm font-medium">{{ $overallProgress }}%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-teal-100">Status</span>
                                <span class="text-sm font-medium">
                                    @if($overallProgress >= 80)
                                        Sangat Baik
                                    @elseif($overallProgress >= 60)
                                        Baik
                                    @elseif($overallProgress >= 40)
                                        Cukup
                                    @else
                                        Perlu Ditingkatkan
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    --}}

    <!-- Main Content Container -->
    <div class="container mx-auto px-4 py-12">
        <!-- Links and Applications Section -->
        <div class="overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-200/50">
                <div class="border-b border-gray-200 bg-gradient-to-r from-teal-50 to-emerald-50 px-6 py-4">
                    <h2 class="text-2xl font-bold text-gray-900">Link dan Aplikasi</h2>
                    <p class="mt-1 text-sm text-gray-600">Akses cepat ke aplikasi dan layanan BPS</p>
                </div>

                <div class="p-6">
                    <!-- Helpful Links -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Helpful Links</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <a href="https://harian2171.bpskepri.com" target="_blank"
                               class="group flex items-center rounded-lg border border-gray-200 p-4 transition-all duration-200 hover:border-teal-300 hover:bg-teal-50 hover:shadow-md">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-blue-600 group-hover:bg-blue-200 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 3v18h18"/>
                                        <path d="m19 9-5 5-4-4-3 3"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-teal-700">Laporan Harian</p>
                                    <p class="text-xs text-gray-600">Provinsi</p>
                                </div>
                                <div class="ml-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 group-hover:text-teal-600">
                                        <path d="M7 17L17 7"/>
                                        <path d="M7 7h10v10"/>
                                    </svg>
                                </div>
                            </a>

                            <a href="https://s.id/link_bps" target="_blank"
                               class="group flex items-center rounded-lg border border-gray-200 p-4 transition-all duration-200 hover:border-teal-300 hover:bg-teal-50 hover:shadow-md">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-600 group-hover:bg-green-200 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-teal-700">Link All Aplikasi</p>
                                    <p class="text-xs text-gray-600">BPS</p>
                                </div>
                                <div class="ml-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 group-hover:text-teal-600">
                                        <path d="M7 17L17 7"/>
                                        <path d="M7 7h10v10"/>
                                    </svg>
                                </div>
                            </a>

                            <a href="https://s.id/monumen" target="_blank"
                               class="group flex items-center rounded-lg border border-gray-200 p-4 transition-all duration-200 hover:border-teal-300 hover:bg-teal-50 hover:shadow-md">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 text-purple-600 group-hover:bg-purple-200 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 20h12"/>
                                        <path d="M12 16V4"/>
                                        <path d="m8 8 4-4 4 4"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-teal-700">MONUMEN</p>
                                    <p class="text-xs text-gray-600">2171</p>
                                </div>
                                <div class="ml-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 group-hover:text-teal-600">
                                        <path d="M7 17L17 7"/>
                                        <path d="M7 7h10v10"/>
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Our Apps -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Our Apps</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
                            <a href="https://monalisa.bpsbatam.com/" target="_blank"
                               class="group flex items-center rounded-lg border border-gray-200 p-4 transition-all duration-200 hover:border-teal-300 hover:bg-teal-50 hover:shadow-md">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 text-orange-600 group-hover:bg-orange-200 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-teal-700">Statistik</p>
                                    <p class="text-xs text-gray-600">Sektoral</p>
                                </div>
                                <div class="ml-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 group-hover:text-teal-600">
                                        <path d="M7 17L17 7"/>
                                        <path d="M7 7h10v10"/>
                                    </svg>
                                </div>
                            </a>

                            <a href="{{ url('/padamunegri') }}"
                               class="group flex items-center rounded-lg border border-gray-200 p-4 transition-all duration-200 hover:border-teal-300 hover:bg-teal-50 hover:shadow-md">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-200 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                                        <line x1="4" y1="22" x2="4" y2="15"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-teal-700">RB Padamu</p>
                                    <p class="text-xs text-gray-600">Negri</p>
                                </div>
                            </a>

                            <a href="{{ route('kms.index') }}"
                               class="group flex items-center rounded-lg border border-gray-200 p-4 transition-all duration-200 hover:border-teal-300 hover:bg-teal-50 hover:shadow-md">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 group-hover:bg-emerald-200 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-teal-700">Knowledge</p>
                                    <p class="text-xs text-gray-600">Management</p>
                                </div>
                            </a>

                            <a href="{{ url('/haloip') }}"
                               class="group flex items-center rounded-lg border border-gray-200 p-4 transition-all duration-200 hover:border-teal-300 hover:bg-teal-50 hover:shadow-md">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-100 text-red-600 group-hover:bg-red-200 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2Z"/>
                                        <path d="M13 5v2"/>
                                        <path d="M13 17v2"/>
                                        <path d="M13 11v2"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-teal-700">Halo IP</p>
                                    <p class="text-xs text-gray-600">IT Tickets</p>
                                </div>
                            </a>

                            <a href="{{ route('employee-performance.index') }}"
                               class="group flex items-center rounded-lg border border-gray-200 p-4 transition-all duration-200 hover:border-teal-300 hover:bg-teal-50 hover:shadow-md">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-blue-600 group-hover:bg-blue-200 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 3v18h18"/>
                                        <path d="m19 9-5 5-4-4-3 3"/>
                                        <circle cx="9" cy="9" r="1"/>
                                        <circle cx="15" cy="15" r="1"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-teal-700">Employee Performance</p>
                                    <p class="text-xs text-gray-600">Integration</p>
                                </div>
                            </a>

                            <a href="{{ route('financial-administration.index') }}"
                               class="group flex items-center rounded-lg border border-gray-200 p-4 transition-all duration-200 hover:border-teal-300 hover:bg-teal-50 hover:shadow-md">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-yellow-100 text-yellow-600 group-hover:bg-yellow-200 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect width="20" height="14" x="2" y="5" rx="2"/>
                                        <line x1="2" x2="22" y1="10" y2="10"/>
                                        <path d="M12 15h.01"/>
                                        <path d="M17 15h.01"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-teal-700">Integration Administrasi</p>
                                    <p class="text-xs text-gray-600">Keuangan</p>
                                </div>
                            </a>

                            <a href="{{ url('/vhts') }}"
                               class="group flex items-center rounded-lg border border-gray-200 p-4 transition-all duration-200 hover:border-teal-300 hover:bg-teal-50 hover:shadow-md">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-teal-100 text-teal-600 group-hover:bg-teal-200 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 12l2 2 4-4"/>
                                        <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.51 0 2.93.37 4.18 1.03"/>
                                        <path d="M16 3.13a9 9 0 0 1 0 17.74"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-teal-700">VHTS Validation</p>
                                    <p class="text-xs text-gray-600">System</p>
                                </div>
                            </a>

                            <!-- Laksamana XLSX Export -->
                            <a href="{{ route('laksamana.export.page') }}"
                               class="group flex items-center rounded-lg border border-gray-200 p-4 transition-all duration-200 hover:border-teal-300 hover:bg-teal-50 hover:shadow-md">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-600 group-hover:bg-green-200 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="16" rx="2"/>
                                        <line x1="7" y1="8" x2="17" y2="8"/>
                                        <line x1="7" y1="12" x2="17" y2="12"/>
                                        <line x1="7" y1="16" x2="12" y2="16"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-teal-700">Laksamana Export</p>
                                    <p class="text-xs text-gray-600">XLSX</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content Container -->

<!-- Dynamic Quotes and Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quotes = [
        "Pantau progres tugas dan kinerja Anda melalui dashboard terintegrasi RENTAK",
        "Kesuksesan adalah hasil dari persiapan, kerja keras, dan belajar dari kegagalan",
        "Kualitas bukan suatu tindakan, tetapi sebuah kebiasaan - Aristoteles",
        "Inovasi membedakan antara pemimpin dan pengikut - Steve Jobs",
        "Keunggulan adalah seni yang diraih melalui latihan dan pembiasaan - Aristoteles",
        "Data adalah aset paling berharga di era digital ini",
        "Statistik yang akurat adalah fondasi kebijakan yang tepat",
        "Bersama-sama kita membangun Indonesia melalui data yang berkualitas",
        "Transparansi dan akuntabilitas dimulai dari data yang terpercaya",
        "Setiap angka yang kita hasilkan berkontribusi untuk kemajuan bangsa"
    ];

    let currentQuoteIndex = 0;
    const quoteElement = document.getElementById('dynamic-quote');

    function changeQuote() {
        // Fade out
        quoteElement.style.opacity = '0';

        setTimeout(() => {
            // Change quote
            currentQuoteIndex = (currentQuoteIndex + 1) % quotes.length;
            quoteElement.textContent = quotes[currentQuoteIndex];

            // Fade in
            quoteElement.style.opacity = '1';
        }, 250);
    }

    // Change quote every 4 seconds
    setInterval(changeQuote, 4000);
});
</script>

@endsection
