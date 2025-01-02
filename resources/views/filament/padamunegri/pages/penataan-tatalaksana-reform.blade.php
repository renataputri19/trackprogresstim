<x-filament::page>
    <div class="space-y-4">

        <!-- Dropdown -->
        <label for="section-select" class="block text-sm font-medium text-gray-900 dark:text-white">Select Section</label>
        <select id="section-select" 
                class="block w-full mt-2 p-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-md shadow-sm" wire:model="selectedCategory"
                onchange="showSection(this.value)">
            <option value="peta-proses-bisnis">i. Peta Proses Bisnis Mempengaruhi Penyederhanaan Jabatan</option>
            <option value="sistem-pemerintahan-spbe">ii. Sistem Pemerintahan Berbasis Elektronik (SPBE) yang Terintegrasi</option>
            <option value="transformasi-digital">iii. Transformasi Digital Memberikan Nilai Manfaat</option>
        </select>
        

        <!-- Sections -->
        <div id="peta-proses-bisnis" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Peta Proses Bisnis Mempengaruhi Penyederhanaan Jabatan'])
        </div>
        <div id="sistem-pemerintahan-spbe" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Sistem Pemerintahan Berbasis Elektronik (SPBE) yang Terintegrasi'])
        </div>
        <div id="transformasi-digital" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Transformasi Digital Memberikan Nilai Manfaat'])
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
        document.getElementById('peta-proses-bisnis').classList.remove('hidden');
    </script>

</x-filament::page>



