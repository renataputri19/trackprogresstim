<?php

namespace App\Livewire\Padamunegri;

use Livewire\Component;
use App\Models\Criterion;
use App\Services\UpdateRowService;

class ManageCriteria extends Component
{
    public $criteria;
    public $selectedCategory;
    public $year;

    public function mount($selectedCategory)
    {
        $this->selectedCategory = $selectedCategory;
        $this->year = (int) session('padamu_year', 2025);
        $this->criteria = Criterion::where('category', $this->selectedCategory)
            ->where('year', $this->year)
            ->get();
    }

    public function render()
    {
        return view('livewire.padamunegri.manage-criteria', ['criteria' => $this->criteria]);
    }

    public function updateRow($id, $field, $value)
    {
        $message = UpdateRowService::update($id, $field, $value);
        session()->flash('message', $message);

        // Refresh the criteria after update
        $this->criteria = Criterion::where('category', $this->selectedCategory)
            ->where('year', $this->year)
            ->get();
    }
}
