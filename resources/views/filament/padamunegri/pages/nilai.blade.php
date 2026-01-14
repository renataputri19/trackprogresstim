<x-filament::page>
    <div class="space-y-8">
        <!-- Year Switcher -->
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Rekapitulasi Nilai Padamu Negeri (Tahun {{ $year ?? session('padamu_year', 2025) }})</h1>
            <x-padamunegri.year-switcher />
        </div>

        <!-- Pemenuhan Section -->
        <div class="overflow-hidden shadow rounded-lg bg-white dark:bg-gray-800 p-4 mb-4">
            <h2 class="text-xl font-bold mb-4 text-center text-gray-900 dark:text-gray-200">Rekapitulasi Nilai Pilar - Pemenuhan</h2>
            <table class="table-auto w-full  border-collapse border border-gray-300 dark:border-gray-600">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Pilar</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Total Bobot</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Total Nilai Unit</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Total Nilai TPI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pemenuhan['pillars'] as $pillar)
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar['pilar'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar['total_bobot'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar['total_nilai_unit'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar['total_nilai_tpi'] }}</td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-100 dark:bg-gray-700 font-bold">
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Total</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $pemenuhan['summary']['total_bobot'] }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $pemenuhan['summary']['total_nilai_unit'] }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $pemenuhan['summary']['total_nilai_tpi'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Reform Section -->
        <div class="overflow-hidden shadow rounded-lg bg-white dark:bg-gray-800 p-4 mb-4">
            <h2 class="text-xl font-bold mb-4 text-gray-900 text-center dark:text-gray-200">Rekapitulasi Nilai Pilar - Reform</h2>
            <table class="table-auto w-full  border-collapse border border-gray-300 dark:border-gray-600">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Pilar</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Total Bobot</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Total Nilai Unit</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Total Nilai TPI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reform['pillars'] as $pillar)
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar['pilar'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar['total_bobot'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar['total_nilai_unit'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar['total_nilai_tpi'] }}</td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-100 dark:bg-gray-700 font-bold">
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Total</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $reform['summary']['total_bobot'] }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $reform['summary']['total_nilai_unit'] }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $reform['summary']['total_nilai_tpi'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Grand Total -->
        <div class="overflow-hidden shadow rounded-lg bg-white dark:bg-gray-800 p-4 mb-4">
            <h2 class="text-xl font-bold mb-4 text-gray-900 text-center dark:text-gray-200">Grand Total</h2>
            <table class="table-auto w-full  border-collapse border border-gray-300 dark:border-gray-600">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Total Bobot</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Total Nilai Unit</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Total Nilai TPI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 font-bold text-gray-800 dark:text-gray-300">{{ $grandTotal['total_bobot'] }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 font-bold text-gray-800 dark:text-gray-300">{{ $grandTotal['total_nilai_unit'] }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 font-bold text-gray-800 dark:text-gray-300">{{ $grandTotal['total_nilai_tpi'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if (!empty($comparison))
            <!-- Comparison 2025 vs 2026 -->
            <div class="overflow-hidden shadow rounded-lg bg-white dark:bg-gray-800 p-4 mb-4">
                <h2 class="text-xl font-bold mb-4 text-gray-900 text-center dark:text-gray-200">Perbandingan Grand Total {{ $comparison['baseline_year'] }} vs {{ $comparison['current_year'] }}</h2>
                <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700">
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200"></th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Grand Total Nilai Unit</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-200">Grand Total Nilai TPI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 font-semibold text-gray-800 dark:text-gray-300">{{ $comparison['baseline_year'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $comparison['baseline']['total_nilai_unit'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $comparison['baseline']['total_nilai_tpi'] }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 font-semibold text-gray-800 dark:text-gray-300">{{ $comparison['current_year'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $comparison['current']['total_nilai_unit'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $comparison['current']['total_nilai_tpi'] }}</td>
                        </tr>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 font-bold text-gray-900 dark:text-gray-200">Δ ({{ $comparison['current_year'] }} - {{ $comparison['baseline_year'] }})</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 font-bold text-gray-800 dark:text-gray-300">{{ $comparison['diff']['total_nilai_unit'] }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 font-bold text-gray-800 dark:text-gray-300">{{ $comparison['diff']['total_nilai_tpi'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-filament::page>
