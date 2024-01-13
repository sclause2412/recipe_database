<?php

namespace App\Livewire\Ingredients;

use App\Actions\Livewire\CleanupInput;
use App\Models\Recipe;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

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
