<x-filament::page>
    <div class="space-y-6">
        <!-- Year Switcher -->
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Rekapitulasi Nilai Padamu Negeri (Tahun {{ $year ?? session('padamu_year', 2025) }})</h1>
            <x-padamunegri.year-switcher />
        </div>

        <!-- Pemenuhan Section -->
        <div class="overflow-hidden shadow rounded-lg bg-white dark:bg-gray-800 p-4">
            <h2 class="text-xl font-bold mb-4 text-center text-gray-900 dark:text-gray-200">Rekapitulasi Nilai Pilar - Pemenuhan</h2>
            <div class="overflow-x-auto overscroll-x-contain scroll-smooth">
            <table class="table-auto w-full min-w-[960px] border-collapse border border-gray-200 dark:border-gray-700">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">Pilar</th>
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">Total Bobot</th>
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">Total Nilai Unit</th>
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">Total Nilai TPI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pemenuhan['pillars'] as $pillar)
                        <tr>
                            <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar['pilar'] }}</td>
                            <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar['total_bobot'] }}</td>
                            <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar['total_nilai_unit'] }}</td>
                            <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar['total_nilai_tpi'] }}</td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-100 dark:bg-gray-700 font-bold">
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">Total</td>
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $pemenuhan['summary']['total_bobot'] }}</td>
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $pemenuhan['summary']['total_nilai_unit'] }}</td>
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $pemenuhan['summary']['total_nilai_tpi'] }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>

        <!-- Reform Section -->
        <div class="overflow-hidden shadow rounded-lg bg-white dark:bg-gray-800 p-4">
            <h2 class="text-xl font-bold mb-4 text-gray-900 text-center dark:text-gray-200">Rekapitulasi Nilai Pilar - Reform</h2>
            <div class="overflow-x-auto overscroll-x-contain scroll-smooth">
            <table class="table-auto w-full min-w-[960px] border-collapse border border-gray-200 dark:border-gray-700">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">Pilar</th>
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">Total Bobot</th>
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">Total Nilai Unit</th>
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">Total Nilai TPI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reform['pillars'] as $pillar)
                        <tr>
                            <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar['pilar'] }}</td>
                            <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar['total_bobot'] }}</td>
                            <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar['total_nilai_unit'] }}</td>
                            <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-800 dark:text-gray-300">{{ $pillar['total_nilai_tpi'] }}</td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-100 dark:bg-gray-700 font-bold">
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">Total</td>
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $reform['summary']['total_bobot'] }}</td>
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $reform['summary']['total_nilai_unit'] }}</td>
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">{{ $reform['summary']['total_nilai_tpi'] }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>

        <!-- Grand Total -->
        <div class="overflow-hidden shadow rounded-lg bg-white dark:bg-gray-800 p-4">
            <h2 class="text-xl font-bold mb-4 text-gray-900 text-center dark:text-gray-200">Grand Total</h2>
            <div class="overflow-x-auto overscroll-x-contain scroll-smooth">
            <table class="table-auto w-full min-w-[640px] border-collapse border border-gray-200 dark:border-gray-700">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">Total Bobot</th>
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">Total Nilai Unit</th>
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-900 dark:text-gray-200">Total Nilai TPI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 font-bold text-gray-800 dark:text-gray-300">{{ $grandTotal['total_bobot'] }}</td>
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 font-bold text-gray-800 dark:text-gray-300">{{ $grandTotal['total_nilai_unit'] }}</td>
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 font-bold text-gray-800 dark:text-gray-300">{{ $grandTotal['total_nilai_tpi'] }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>

        
    </div>
</x-filament::page>
