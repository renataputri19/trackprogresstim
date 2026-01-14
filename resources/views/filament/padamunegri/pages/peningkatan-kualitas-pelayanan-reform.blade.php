<x-filament::page>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Peningkatan Kualitas Pelayanan Publik - REFORM (Tahun {{ session('padamu_year', 2025) }})</h1>
            <x-padamunegri.year-switcher />
        </div>

        <!-- Dropdown -->
        <label for="section-select" class="block text-sm font-medium text-gray-900 dark:text-white">Select Section</label>
        <select id="section-select" 
                class="block w-full mt-2 p-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-md shadow-sm" wire:model="selectedCategory"
                onchange="showSection(this.value)">
            <option value="upaya-inovasi">i. Upaya dan/atau Inovasi Pelayanan Publik</option>
            <option value="pengaduan-konsultasi">ii. Penanganan Pengaduan Pelayanan dan Konsultasi</option>
        </select>


        <!-- Sections -->
        <div id="upaya-inovasi" class="section hidden">
            <!-- Sub-section for a. Kemudahan Persyaratan -->
            <div class="mb-6">
                {{-- <h2 class="text-lg font-semibold mb-4">a. Kemudahan Persyaratan</h2> --}}
                @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Peningkatan Kualitas Pelayanan Publik - Criteria'])
            </div>

            <!-- Sub-section for b. Persentase penyederhanaan perizinan -->
            <div class="mt-6">
                {{-- <h2 class="text-lg font-semibold mb-4">b. Persentase penyederhanaan perizinan</h2> --}}
                @livewire('padamunegri.manage-reform', ['selectedCategory' => 'Peningkatan Kualitas Pelayanan Publik - Reform'])
            </div>
        </div>

        <div id="pengaduan-konsultasi" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Penanganan Pengaduan Pelayanan dan Konsultasi'])
        </div>
        
    </div>



    <!-- JavaScript to toggle sections -->
    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById(sectionId).classList.remove('hidden');
        }

        // Set default visible section
        document.getElementById('upaya-inovasi').classList.remove('hidden');
    </script>

</x-filament::page>



