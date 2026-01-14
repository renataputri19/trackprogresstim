<x-filament::page>
    <div class="space-y-8">
        <!-- Heading -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">Perbandingan Nilai ({{ $baselineYear }} vs {{ $currentYear }})</h1>
        </div>

        <!-- Grand Total Comparison (moved from Nilai page, expanded with % change) -->
        <div class="shadow rounded-xl bg-white dark:bg-gray-800 p-6 mb-4">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 text-center dark:text-gray-200">Perbandingan Grand Total {{ $comparison['baseline_year'] }} vs {{ $comparison['current_year'] }}</h2>
            <div class="overflow-x-auto overscroll-x-contain scroll-smooth">
            <table class="table-auto w-full min-w-[720px] border-collapse border border-gray-200 dark:border-gray-700 text-sm md:text-base">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200 text-left"></th>
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200 text-left">Grand Total Nilai Unit</th>
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200 text-left">Grand Total Nilai TPI</th>
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200 text-left">Perubahan (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 font-semibold text-gray-800 dark:text-gray-300">{{ $comparison['baseline_year'] }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $comparison['grand_total']['baseline']['total_nilai_unit'] }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $comparison['grand_total']['baseline']['total_nilai_tpi'] }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">-</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 font-semibold text-gray-800 dark:text-gray-300">{{ $comparison['current_year'] }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $comparison['grand_total']['current']['total_nilai_unit'] }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $comparison['grand_total']['current']['total_nilai_tpi'] }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">
                            @php
                                $pctUnit = $comparison['grand_total']['pct']['total_nilai_unit'];
                                $pctTpi = $comparison['grand_total']['pct']['total_nilai_tpi'];
                            @endphp
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ (float)($pctUnit ?? 0) >= 0 ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200' }}">
                                    Nilai Unit: {{ $pctUnit !== null ? $pctUnit . '%' : 'N/A' }}
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ (float)($pctTpi ?? 0) >= 0 ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200' }}">
                                    Nilai TPI: {{ $pctTpi !== null ? $pctTpi . '%' : 'N/A' }}
                                </span>
                            </div>
                        </td>
                    </tr>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 font-semibold text-gray-900 dark:text-gray-200">Δ ({{ $comparison['current_year'] }} - {{ $comparison['baseline_year'] }})</td>
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 font-semibold text-gray-800 dark:text-gray-300">{{ $comparison['grand_total']['diff']['total_nilai_unit'] }}</td>
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 font-semibold text-gray-800 dark:text-gray-300">{{ $comparison['grand_total']['diff']['total_nilai_tpi'] }}</td>
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 font-semibold text-gray-800 dark:text-gray-300">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>

        <!-- Pemenuhan Breakdown -->
        <div class="shadow rounded-xl bg-white dark:bg-gray-800 p-6 mb-4">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 text-center dark:text-gray-200">Perbandingan Pilar - Pemenuhan</h2>
            <div class="overflow-x-auto overscroll-x-contain scroll-smooth">
            <table class="table-auto w-full min-w-[960px] border-collapse border border-gray-200 dark:border-gray-700 text-sm md:text-base">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200 text-left">Pilar</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $baselineYear }} Nilai Unit</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $currentYear }} Nilai Unit</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Δ Unit</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $baselineYear }} Nilai TPI</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $currentYear }} Nilai TPI</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Δ TPI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pemenuhan2025['pillars'] as $index => $pillar25)
                        @php
                            $pillar26 = $pemenuhan2026['pillars'][$index] ?? null;
                            $unit25 = isset($pillar25['total_nilai_unit']) ? (float) $pillar25['total_nilai_unit'] : 0.0;
                            $unit26 = isset($pillar26['total_nilai_unit']) ? (float) $pillar26['total_nilai_unit'] : 0.0;
                            $tpi25 = isset($pillar25['total_nilai_tpi']) ? (float) $pillar25['total_nilai_tpi'] : 0.0;
                            $tpi26 = isset($pillar26['total_nilai_tpi']) ? (float) $pillar26['total_nilai_tpi'] : 0.0;
                            $diffUnit = number_format($unit26 - $unit25, 2);
                            $diffTpi = number_format($tpi26 - $tpi25, 2);
                        @endphp
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar25['pilar'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar25['total_nilai_unit'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar26['total_nilai_unit'] ?? '0.00' }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ (float) $diffUnit >= 0 ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200' }}">{{ $diffUnit }}</span>
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar25['total_nilai_tpi'] }}</td>
                            <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar26['total_nilai_tpi'] ?? '0.00' }}</td>
                            <td class="border border-gray-200 dark:border-gray-700 px-4 py-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ (float) $diffTpi >= 0 ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200' }}">{{ $diffTpi }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>

        <!-- Reform Breakdown -->
        <div class="shadow rounded-xl bg-white dark:bg-gray-800 p-6 mb-4">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 text-center dark:text-gray-200">Perbandingan Pilar - Reform</h2>
            <div class="overflow-x-auto overscroll-x-contain scroll-smooth">
            <table class="table-auto w-full min-w-[960px] border-collapse border border-gray-200 dark:border-gray-700 text-sm md:text-base">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200 text-left">Pilar</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $baselineYear }} Nilai Unit</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $currentYear }} Nilai Unit</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Δ Unit</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $baselineYear }} Nilai TPI</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $currentYear }} Nilai TPI</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Δ TPI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reform2025['pillars'] as $index => $pillar25)
                        @php
                            $pillar26 = $reform2026['pillars'][$index] ?? null;
                            $unit25 = isset($pillar25['total_nilai_unit']) ? (float) $pillar25['total_nilai_unit'] : 0.0;
                            $unit26 = isset($pillar26['total_nilai_unit']) ? (float) $pillar26['total_nilai_unit'] : 0.0;
                            $tpi25 = isset($pillar25['total_nilai_tpi']) ? (float) $pillar25['total_nilai_tpi'] : 0.0;
                            $tpi26 = isset($pillar26['total_nilai_tpi']) ? (float) $pillar26['total_nilai_tpi'] : 0.0;
                            $diffUnit = number_format($unit26 - $unit25, 2);
                            $diffTpi = number_format($tpi26 - $tpi25, 2);
                        @endphp
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar25['pilar'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar25['total_nilai_unit'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar26['total_nilai_unit'] ?? '0.00' }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ (float) $diffUnit >= 0 ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200' }}">{{ $diffUnit }}</span>
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar25['total_nilai_tpi'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar26['total_nilai_tpi'] ?? '0.00' }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ (float) $diffTpi >= 0 ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200' }}">{{ $diffTpi }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
</x-filament::page>