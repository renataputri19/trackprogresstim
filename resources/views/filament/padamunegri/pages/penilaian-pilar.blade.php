<x-filament::page>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Penilaian Pilar Pemenuhan (Tahun {{ session('padamu_year', 2025) }})</h1>
            <x-padamunegri.year-switcher />
        </div>

        @foreach ($pemenuhan['pillars'] as $pilar)
            <div class="overflow-hidden shadow rounded-lg bg-white dark:bg-gray-800 p-4">
                <h2 class="text-xl font-bold text-center mb-4 text-gray-900 dark:text-gray-200">{{ $pilar['pilar'] }}</h2>
                <div class="overflow-x-auto overscroll-x-contain scroll-smooth">
                <table class="table-auto w-full min-w-[960px] border-collapse border border-gray-200 dark:border-gray-700">
                    <thead>
                        <tr>
                            <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">Penilaian</th>
                            <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">Bobot</th>
                            <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">Nilai Unit</th>
                            <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">Nilai TPI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pilar['categories'] as $category)
                            <tr>
                                <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-800 dark:text-gray-200">{{ $category['penilaian'] }}</td>
                                <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-800 dark:text-gray-200">{{ $category['bobot'] }}</td>
                                <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-800 dark:text-gray-200">{{ $category['nilai_unit'] }}</td>
                                <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-gray-800 dark:text-gray-200">{{ $category['nilai_tpi'] }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 font-bold bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">Total</td>
                            <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 font-bold text-gray-800 dark:text-gray-200">{{ $pilar['total_bobot'] }}</td>
                            <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 font-bold text-gray-800 dark:text-gray-200">{{ $pilar['total_nilai_unit'] }}</td>
                            <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 font-bold text-gray-800 dark:text-gray-200">{{ $pilar['total_nilai_tpi'] }}</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        @endforeach

        <!-- Grand Total -->
        <div class="overflow-hidden shadow rounded-lg bg-white dark:bg-gray-800 p-4">
            <h2 class="text-xl font-bold mb-4 text-center text-gray-900 dark:text-gray-200">Grand Total</h2>
            <div class="overflow-x-auto overscroll-x-contain scroll-smooth">
            <table class="table-auto w-full min-w-[640px] border-collapse border border-gray-200 dark:border-gray-700">
                <thead>
                    <tr>
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">Total Bobot</th>
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">Total Nilai Unit</th>
                        <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">Total Nilai TPI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 font-bold text-gray-800 dark:text-gray-200">{{ $pemenuhan['summary']['total_bobot'] }}</td>
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 font-bold text-gray-800 dark:text-gray-200">{{ $pemenuhan['summary']['total_nilai_unit'] }}</td>
                        <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 font-bold text-gray-800 dark:text-gray-200">{{ $pemenuhan['summary']['total_nilai_tpi'] }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</x-filament::page>
