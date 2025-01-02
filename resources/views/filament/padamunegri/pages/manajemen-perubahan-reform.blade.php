<x-filament::page>
    <div class="space-y-4">
        <!-- Title -->
        <h1 class="text-2xl font-bold">Manajemen Perubahan - Reform</h1>

        <!-- Dropdown -->
        <label for="section-select" class="block text-sm font-medium text-gray-900 dark:text-white">Select Section</label>
        <select id="section-select" 
                class="block w-full mt-2 p-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-md shadow-sm"
                wire:model="selectedCategory" onchange="showSection(this.value)">
            <option value="komitmen-dalam-perubahan">i. Komitmen dalam Perubahan</option>
            <option value="komitmen-pimpinan">ii. Komitmen Pimpinan</option>
            <option value="membangun-budaya-kerja">iii. Membangun Budaya Kerja</option>
        </select>

        <!-- Sections -->
        <div id="komitmen-dalam-perubahan" class="section hidden">
            @livewire('padamunegri.manage-reform', ['selectedCategory' => 'Komitmen dalam Perubahan'])
        </div>
        <div id="komitmen-pimpinan" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Komitmen Pimpinan'])
        </div>
        <div id="membangun-budaya-kerja" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Membangun Budaya Kerja'])
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
        document.getElementById('komitmen-dalam-perubahan').classList.remove('hidden');
    </script>
</x-filament::page>
