<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use Livewire\WithPagination;

class Recipes extends Component
{
    use WithPagination;

    public $category;

    public function render()
    {
        $recipes = $this->category->recipes()->orderBy('name');
        return view('livewire.recipes.list', ['recipes' => $recipes->paginate(10, ['*'], 'page')]);
    }
}
