<?php

namespace App\Livewire\Units;

use Livewire\Component;
use Livewire\WithPagination;

class Recipes extends Component
{
    use WithPagination;

    public $unit;

    public function render()
    {
        $recipes = $this->unit->recipes->sortBy('name');
        return view('livewire.recipes.list', ['recipes' => $recipes->paginate(10, ['*'], 'page')]);
    }
}
