<?php

namespace App\Livewire\Padamunegri;

use Livewire\Component;
use App\Models\Criterion;
use App\Services\UpdateRowService;

class ManageReform extends Component
{
    public $criteria;
    public $selectedCategory;
    public $year;

    public function mount($selectedCategory)
    {
        $this->selectedCategory = $selectedCategory;
        $this->year = (int) session('padamu_year', 2025);
        $this->loadCriteria();
    }

    public function render()
    {
        return view('livewire.padamunegri.manage-reform', ['criteria' => $this->criteria]);
    }

    public function updateRow($id, $field, $value)
    {
        $message = UpdateRowService::update($id, $field, $value);
        session()->flash('message', $message);

        // Refresh criteria after update
        $this->loadCriteria();
    }

    private function loadCriteria()
    {
        $this->criteria = Criterion::where('category', $this->selectedCategory)
            ->where('year', $this->year)
            ->get();
    }
}


