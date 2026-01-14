<x-filament::page>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Peningkatan Kualitas Pelayanan Publik - PEMENUHAN (Tahun {{ session('padamu_year', 2025) }})</h1>
            <x-padamunegri.year-switcher />
        </div>

        <!-- Dropdown -->
        <label for="section-select" class="block text-sm font-medium text-gray-900 dark:text-white">Select Section</label>
        <select id="section-select" 
                class="block w-full mt-2 p-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-md shadow-sm" wire:model="selectedCategory"
                onchange="showSection(this.value)">
            <option value="standar-pelayanan">i. Standar Pelayanan</option>
            <option value="budaya-pelayanan-prima">ii. Budaya Pelayanan Prima</option>
            <option value="pengelolaan-pengaduan">iii. Pengelolaan Pengaduan</option>
            <option value="penilaian-kepuasan">iv. Penilaian Kepuasan terhadap Pelayanan</option>
            <option value="pemanfaatan-teknologi">v. Pemanfaatan Teknologi Informasi</option>
        </select>


        <!-- Sections -->
        <div id="standar-pelayanan" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Standar Pelayanan'])
        </div>

        <div id="budaya-pelayanan-prima" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Budaya Pelayanan Prima'])
        </div>

        <div id="pengelolaan-pengaduan" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Pengelolaan Pengaduan'])
        </div>

        <div id="penilaian-kepuasan" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Penilaian Kepuasan'])
        </div>

        <div id="pemanfaatan-teknologi" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Pemanfaatan Teknologi Informasi'])
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
        document.getElementById('standar-pelayanan').classList.remove('hidden');
    </script>

</x-filament::page>



