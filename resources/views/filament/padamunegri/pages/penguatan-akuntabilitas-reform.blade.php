<x-filament::page>
    <div class="space-y-4">

        <!-- Dropdown -->
        <label for="section-select" class="block text-sm font-medium text-gray-900 dark:text-white">Select Section</label>
        <select id="section-select" 
                class="block w-full mt-2 p-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-md shadow-sm" wire:model="selectedCategory"
                onchange="showSection(this.value)">
            <option value="meningkatnya-capaian-kinerja-unit-kerja">i. Meningkatnya Capaian Kinerja Unit Kerja</option>
            <option value="pemberian-reward-and-punishment">ii. Pemberian Reward and Punishment</option>
            <option value="kerangka-logis-kinerja">iii. Kerangka Logis Kinerja</option>
        </select>
        



        <!-- Sections -->
        <div id="meningkatnya-capaian-kinerja-unit-kerja" class="section hidden">
            @livewire('padamunegri.manage-reform', ['selectedCategory' => 'Meningkatnya Capaian Kinerja Unit Kerja'])
        </div>
        <div id="pemberian-reward-and-punishment" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Pemberian Reward and Punishment'])
        </div>
        <div id="kerangka-logis-kinerja" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Kerangka Logis Kinerja'])
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
        document.getElementById('meningkatnya-capaian-kinerja-unit-kerja').classList.remove('hidden');
    </script>

</x-filament::page>



