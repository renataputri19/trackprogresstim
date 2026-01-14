<x-filament::page>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Penguatan Pengawasan - REFORM (Tahun {{ session('padamu_year', 2025) }})</h1>
            <x-padamunegri.year-switcher />
        </div>

        <!-- Dropdown -->
        <label for="section-select" class="block text-sm font-medium text-gray-900 dark:text-white">Select Section</label>
        <select id="section-select" 
                class="block w-full mt-2 p-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-md shadow-sm" wire:model="selectedCategory"
                onchange="showSection(this.value)">
            <option value="mekanisme-pengendalian">i. Mekanisme Pengendalian</option>
            <option value="penanganan-pengaduan-masyarakat">ii. Penanganan Pengaduan Masyarakat</option>
            <option value="penyampaian-laporan-harta-kekayaan">iii. Penyampaian Laporan Harta Kekayaan</option>
        </select>
        


         <!-- Sections -->
         <div id="mekanisme-pengendalian" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Mekanisme Pengendalian'])
        </div>
        <div id="penanganan-pengaduan-masyarakat" class="section hidden">
            @livewire('padamunegri.manage-reform', ['selectedCategory' => 'Penanganan Pengaduan Masyarakat'])
        </div>
        <div id="penyampaian-laporan-harta-kekayaan" class="section hidden">
            @livewire('padamunegri.manage-reform', ['selectedCategory' => 'Penyampaian Laporan Harta Kekayaan'])
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
        document.getElementById('mekanisme-pengendalian').classList.remove('hidden');
    </script>

</x-filament::page>



