@php
    $allowedYears = [2025, 2026];
    $currentYear = (int) session('padamu_year', 2025);
    $currentIndex = array_search($currentYear, $allowedYears, true);
    $prevYear = $allowedYears[max(0, ($currentIndex === false ? 0 : $currentIndex - 1))];
    $nextYear = $allowedYears[min(count($allowedYears) - 1, ($currentIndex === false ? 0 : $currentIndex + 1))];
@endphp

<form method="POST" action="{{ route('padamunegri.set-year') }}" id="padamu-year-form" class="flex items-center gap-3">
    @csrf

    <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">Tahun</span>

    <div class="flex items-center gap-2">
        <!-- Segmented year control: accessible, high-contrast, Filament-consistent -->
        <div
            role="group"
            aria-label="Pilih tahun"
            class="inline-flex isolate rounded-md bg-white dark:bg-gray-900 ring-1 ring-gray-300 dark:ring-gray-700 shadow-sm divide-x divide-gray-300 dark:divide-gray-700 overflow-hidden"
        >
            @foreach ($allowedYears as $year)
                @php($isActive = $year === $currentYear)
                <button
                    type="button"
                    data-padamu-year="{{ $year }}"
                    class="relative min-w-[3.25rem] px-3 py-1.5 text-sm font-semibold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-gray-900 {{ $isActive ? 'bg-primary-600 text-white hover:bg-primary-700' : 'bg-transparent text-gray-800 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800/60' }}"
                    aria-pressed="{{ $isActive ? 'true' : 'false' }}"
                    aria-current="{{ $isActive ? 'true' : 'false' }}"
                    title="Pilih tahun {{ $year }}"
                >
                    {{ $year }}
                </button>
            @endforeach
        </div>

        <!-- Prev / Next controls -->
        <div class="flex items-center gap-1">
            <button
                type="button"
                data-padamu-year="{{ $prevYear }}"
                class="p-2 rounded-md bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 ring-1 ring-gray-300 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/60 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-gray-900"
                title="Tahun sebelumnya"
                aria-label="Tahun sebelumnya"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M15.75 19.5a.75.75 0 0 1-.53-.22l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.06 12l7.22 7.22a.75.75 0 0 1-.53 1.28Z" clip-rule="evenodd" />
                </svg>
            </button>
            <button
                type="button"
                data-padamu-year="{{ $nextYear }}"
                class="p-2 rounded-md bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 ring-1 ring-gray-300 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/60 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-gray-900"
                title="Tahun berikutnya"
                aria-label="Tahun berikutnya"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M8.25 4.5a.75.75 0 0 1 .53.22l7.5 7.5a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 1 1-1.06-1.06L14.94 12 7.72 4.78a.75.75 0 0 1 .53-1.28Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>

    <input type="hidden" name="year" id="padamu-year" value="{{ $currentYear }}" />
</form>

<script>
    (function () {
        const form = document.getElementById('padamu-year-form');
        const hidden = document.getElementById('padamu-year');
        if (!form || !hidden) return;

        function submitWithYear(year) {
            hidden.value = year;
            form.submit();
        }

        document.querySelectorAll('[data-padamu-year]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const year = parseInt(this.getAttribute('data-padamu-year'), 10);
                if (!isNaN(year)) submitWithYear(year);
            });
        });
    })();
</script>