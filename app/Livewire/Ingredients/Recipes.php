<?php

namespace App\Livewire\Ingredients;

use Livewire\Component;
use Livewire\WithPagination;

class Recipes extends Component
{
    use WithPagination;

    public $ingredient;

    public function render()
    {
        $recipes = $this->ingredient->recipes->sortBy('name');
        return view('livewire.recipes.list', ['recipes' => $recipes->paginate(10, ['*'], 'page')]);
    }
}
