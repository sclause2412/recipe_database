<?php

namespace App\Livewire\Categories;

use App\Actions\Livewire\CleanupInput;
use App\Models\Recipe;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

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
