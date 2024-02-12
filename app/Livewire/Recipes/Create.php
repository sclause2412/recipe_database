<?php

namespace App\Livewire\Recipes;

use App\Actions\Files\HasImage;
use App\Actions\Livewire\CleanupInput;
use App\Models\Category;
use App\Models\Recipe;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

class Create extends Component
{
    use CleanupInput;
    use WireUiActions;
    use WithFileUploads;
    use HasImage;

    public $name = null;
    public $picture = null;
    public $category = null;
    public $cooked = false;
    public $source = null;
    public $portions = null;
    public $time = null;
    public $description = null;
    public $active = false;
    public $actpicture = null;

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
            'picture' => ['nullable', 'mimes:jpg,jpeg,png'],
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
        if (!is_null($this->picture)) {
            $id = md5(uniqid());
            $path = $this->uploadImage($this->picture, 'recipes/' . $id, ['width' => 1500, 'height' => 500]);
            if ($path !== null) {
                $recipe->picture = $id;
            }
            $this->picture = null;
        }
        $recipe->save();

        return redirect()->route('recipes.edit', $recipe);
    }
}
