<?php

namespace App\Livewire\Recipes;

use App\Actions\Livewire\CleanupInput;
use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Edit extends Component
{
    use CleanupInput;
    use WireUiActions;

    public $recipe = null;
    public $name = null;
    public $category = null;
    public $cooked = false;
    public $source = null;
    public $portions = null;
    public $time = null;
    public $description = null;
    public $active = false;


    public function mount($recipe)
    {
        $this->recipe = $recipe;
        $this->name = $this->recipe->name;
        $this->category = $this->recipe->category?->id;
        $this->cooked = $this->recipe->cooked;
        $this->source = $this->recipe->source;
        $this->portions = $this->recipe->portions;
        $this->time = $this->recipe->time;
        $this->description = $this->recipe->description;
        $this->active = $this->recipe->active;
    }

    public function render()
    {
        return view('livewire.recipes.edit');
    }

    public function saveRecipe()
    {
        $this->authorize('update', $this->recipe);

        $recipe = $this->recipe;

        $this->name = $this->cleanInput($this->name);
        $this->category = $this->cleanInput($this->category);
        $this->cooked = $this->cleanInput($this->cooked);
        $this->source = $this->cleanInput($this->source);
        $this->portions = $this->cleanInput($this->portions);
        $this->time = $this->cleanInput($this->time);
        $this->description = $this->cleanInput($this->description);
        $this->active = $this->cleanInput($this->active);

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required'],
            'cooked' => ['boolean'],
            'portions' => ['nullable', 'integer', 'gte:0'],
            'time' => ['nullable', 'integer', 'gte:0'],
            'active' => ['boolean'],
        ]);

        $category = Category::where('id', $this->category)->first();

        if (is_null($category)) {
            $category = new Category;
            $category->name = $this->category;
            $category->save();
        }

        $recipe->name = $this->name;
        $recipe->category_id = $category->id;
        $recipe->cooked = $this->cooked;
        $recipe->source = $this->source;
        $recipe->portions = $this->portions;
        $recipe->time = $this->time;
        $recipe->description = $this->description;
        $recipe->active = $this->active;
        $recipe->save();

        $this->notification()->success(__('Recipe saved'), __('The recipe was successfully saved'));
    }
}
