<x-filament::page>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Penataan Sistem Manajemen SDM Aparatur - PEMENUHAN (Tahun {{ session('padamu_year', 2025) }})</h1>
            <x-padamunegri.year-switcher />
        </div>

        <!-- Dropdown -->
        <label for="section-select" class="block text-sm font-medium text-gray-900 dark:text-white">Select Section</label>
        <select id="section-select" 
                class="block w-full mt-2 p-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-md shadow-sm" wire:model="selectedCategory"
                onchange="showSection(this.value)">
            <option value="perencanaan-kebutuhan">i. Perencanaan Kebutuhan Pegawai sesuai dengan Kebutuhan Organisasi</option>
            <option value="pola-mutasi-internal">ii. Pola Mutasi Internal</option>
            <option value="pengembangan-pegawai">iii. Pengembangan Pegawai Berbasis Kompetensi</option>
            <option value="penetapan-kinerja-individu">iv. Penetapan Kinerja Individu</option>
            <option value="penegakan-aturan">v. Penegakan Aturan Disiplin/Kode Etik/Kode Perilaku Pegawai</option>
            <option value="sistem-informasi">vi. Sistem Informasi Kepegawaian</option>
        </select>
        

        <!-- Sections -->
        <div id="perencanaan-kebutuhan" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Perencanaan Kebutuhan Pegawai'])
        </div>

        <div id="pola-mutasi-internal" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Pola Mutasi Internal'])
        </div>

        <div id="pengembangan-pegawai" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Pengembangan Pegawai'])
        </div>

        <div id="penetapan-kinerja-individu" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Penetapan Kinerja Individu'])
        </div>

        <div id="penegakan-aturan" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Penegakan Aturan'])
        </div>

        <div id="sistem-informasi" class="section hidden">
            @livewire('padamunegri.manage-criteria', ['selectedCategory' => 'Sistem Informasi Kepegawaian'])
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
        document.getElementById('perencanaan-kebutuhan').classList.remove('hidden');
    </script>

</x-filament::page>



