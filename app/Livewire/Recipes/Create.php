<?php

namespace App\Livewire\Recipes;

use App\Actions\Livewire\CleanupInput;
use App\Models\Category;
use App\Models\Recipe;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Create extends Component
{
    use CleanupInput;
    use WireUiActions;

    public $name = null;
    public $category = null;
    public $cooked = false;
    public $source = null;
    public $portions = null;
    public $time = null;
    public $description = null;
    public $active = false;

    public function render()
    {
        return view('livewire.recipes.edit');
    }

    public function saveRecipe()
    {
        $this->authorize('create', Recipe::class);

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
            $category = new Category();
            $category->name = $this->category;
            $category->save();
        }

        $recipe = new Recipe();
        $recipe->name = $this->name;
        $recipe->category_id = $category->id;
        $recipe->cooked = $this->cooked;
        $recipe->source = $this->source;
        $recipe->portions = $this->portions;
        $recipe->time = $this->time;
        $recipe->description = $this->description;
        $recipe->active = $this->active;
        $recipe->save();

        return redirect()->route('recipes.edit', $recipe);
    }
}
