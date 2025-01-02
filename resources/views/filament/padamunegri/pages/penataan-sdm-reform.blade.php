<x-filament::page>
    <div class="space-y-4">

        <!-- Dropdown -->
        <label for="section-select" class="block text-sm font-medium text-gray-900 dark:text-white">Select Section</label>
        <select id="section-select" 
                class="block w-full mt-2 p-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-md shadow-sm" wire:model="selectedCategory"
                onchange="showSection(this.value)">
            <option value="kinerja-individu">i. Kinerja Individu</option>
            <option value="assessment-pegawai">ii. Assessment Pegawai</option>
            <option value="pelanggaran-disiplin">iii. Pelanggaran Disiplin Pegawai</option>
        </select>
        

        <!-- Sections -->
        <div id="kinerja-individu" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Kinerja Individu'])
        </div>
        <div id="assessment-pegawai" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Assessment Pegawai'])
        </div>
        <div id="pelanggaran-disiplin" class="section hidden">
            @livewire('padamunegri.manage-reform', ['selectedCategory' => 'Pelanggaran Disiplin Pegawai'])
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
        document.getElementById('kinerja-individu').classList.remove('hidden');
    </script>

</x-filament::page>



