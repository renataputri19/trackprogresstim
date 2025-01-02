<x-filament::page>
    <div class="space-y-4">

        <!-- Dropdown -->
        <label for="section-select" class="block text-sm font-medium text-gray-900 dark:text-white">Select Section</label>
        <select id="section-select" 
                class="block w-full mt-2 p-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-md shadow-sm" wire:model="selectedCategory"
                onchange="showSection(this.value)">
            <option value="pengendalian-gratifikasi">i. Pengendalian Gratifikasi</option>
            <option value="penerapan-spip">ii. Penerapan Sistem Pengendalian Intern Pemerintah (SPIP)</option>
            <option value="pengaduan-masyarakat">iii. Pengaduan Masyarakat</option>
            <option value="whistle-blowing-system">iv. Whistle-Blowing System</option>
            <option value="penanganan-benturan-kepentingan">v. Penanganan Benturan Kepentingan</option>
        </select>
        


        <!-- Sections -->
        <div id="pengendalian-gratifikasi" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Pengendalian Gratifikasi'])
        </div>

        <div id="penerapan-spip" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Penerapan SPIP'])
        </div>

        <div id="pengaduan-masyarakat" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Pengaduan Masyarakat'])
        </div>

        <div id="whistle-blowing-system" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Whistle-Blowing System'])
        </div>

        <div id="penanganan-benturan-kepentingan" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Penanganan Benturan Kepentingan'])
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
        document.getElementById('pengendalian-gratifikasi').classList.remove('hidden');
    </script>

</x-filament::page>



