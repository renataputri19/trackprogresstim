<x-filament::page>
    <div class="space-y-4">
        <!-- Title -->
        {{-- <h1 class="text-2xl font-bold">1. Manajemen Perubahan - Pemenuhan</h1> --}}

        <!-- Dropdown -->
        <label for="section-select" class="block text-sm font-medium text-gray-900 dark:text-white">Select Section</label>
        <select id="section-select" 
                class="block w-full mt-2 p-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-md shadow-sm" wire:model="selectedCategory"
                onchange="showSection(this.value)">
            <option value="penyusunan-tim-kerja">i. Penyusunan Tim Kerja</option>
            <option value="rencana-pembangunan-zi">ii. Rencana Pembangunan Zona Integritas</option>
            <option value="pemantauan-evaluasi">iii. Pemantauan dan Evaluasi Pembangunan WBK/WBBM</option>
            <option value="perubahan-budaya-kerja">iv. Perubahan Pola Pikir dan Budaya Kerja</option>
        </select>


        


        <!-- Sections -->
        <div id="penyusunan-tim-kerja" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Penyusunan Tim Kerja'])
        </div>
        <div id="rencana-pembangunan-zi" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Rencana Pembangunan Zona Integritas'])
        </div>
        <div id="pemantauan-evaluasi" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Pemantauan dan Evaluasi Pembangunan WBK/WBBM'])
        </div>
        <div id="perubahan-budaya-kerja" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Perubahan Pola Pikir dan Budaya Kerja'])
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
        document.getElementById('penyusunan-tim-kerja').classList.remove('hidden');
    </script>
</x-filament::page>
