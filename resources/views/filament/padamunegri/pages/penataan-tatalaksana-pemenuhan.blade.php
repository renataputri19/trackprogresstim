<x-filament::page>
    <div class="space-y-4">

        <!-- Dropdown -->
        <label for="section-select" class="block text-sm font-medium text-gray-900 dark:text-white">Select Section</label>
        <select id="section-select" 
                class="block w-full mt-2 p-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-md shadow-sm" wire:model="selectedCategory"
                onchange="showSection(this.value)">
            <option value="prosedur-operasional">i. Prosedur Operasional Tetap (SOP) Kegiatan Utama</option>
            <option value="sistem-pemerintahan">ii. Sistem Pemerintahan Berbasis Elektronik (SPBE)</option>
            <option value="keterbukaan-informasi">iii. Keterbukaan Informasi Publik</option>
        </select>
        

        <!-- Sections -->
        <div id="prosedur-operasional" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Prosedur Operasional Tetap (SOP) Kegiatan Utama'])
        </div>

        <div id="sistem-pemerintahan" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Sistem Pemerintahan Berbasis Elektronik (SPBE)'])
        </div>

        <div id="keterbukaan-informasi" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Keterbukaan Informasi Publik'])
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
        document.getElementById('prosedur-operasional').classList.remove('hidden');
    </script>

</x-filament::page>



