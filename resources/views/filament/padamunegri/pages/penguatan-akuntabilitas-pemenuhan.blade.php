<x-filament::page>
    <div class="space-y-4">

        <!-- Dropdown -->
        <label for="section-select" class="block text-sm font-medium text-gray-900 dark:text-white">Select Section</label>
        <select id="section-select" 
                class="block w-full mt-2 p-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-md shadow-sm" wire:model="selectedCategory"
                onchange="showSection(this.value)">
            <option value="keterlibatan-pimpinan">i. Keterlibatan Pimpinan</option>
            <option value="pengelolaan-akuntabilitas">ii. Pengelolaan Akuntabilitas Kinerja</option>
        </select>
        



        <!-- Sections -->
        <div id="keterlibatan-pimpinan" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Keterlibatan Pimpinan'])
        </div>

        <div id="pengelolaan-akuntabilitas" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Pengelolaan Akuntabilitas Kinerja'])
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
        document.getElementById('keterlibatan-pimpinan').classList.remove('hidden');
    </script>

</x-filament::page>



