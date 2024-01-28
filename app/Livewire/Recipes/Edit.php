<?php

namespace App\Livewire\Recipes;

use App\Actions\Files\HasImage;
use App\Actions\Livewire\CleanupInput;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

class Edit extends Component
{
    use CleanupInput;
    use WireUiActions;
    use WithFileUploads;
    use HasImage;

    public $recipe = null;
    public $picture = false;
    public $name = null;
    public $category = null;
    public $cooked = false;
    public $source = null;
    public $portions = null;
    public $time = null;
    public $description = null;
    public $active = false;
    public $actpicture = false;


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

        $this->actpicture = $this->getImage('recipes/' . $recipe->picture);
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

        $recipe->name = $this->name;
        $recipe->category_id = $category->id;
        $recipe->cooked = $this->cooked;
        $recipe->source = $this->source;
        $recipe->portions = $this->portions;
        $recipe->time = $this->time;
        $recipe->description = $this->description;
        $recipe->active = $this->active;
        if ($this->picture) {
            if (!is_null($this->recipe->picture)) {
                $this->deleteImage('recipes/' . $this->recipe->picture);
            }
            $id = md5(uniqid());
            $path = $this->uploadImage($this->picture, 'recipes/' . $id, ['width' => 1500, 'height' => 500]);
            if ($path !== null) {
                $recipe->picture = $id;
            }
            $this->picture = false;
            $this->actpicture = $this->getImage('recipes/' . $recipe->picture);
        }
        $recipe->save();

        $this->notification()->success(__('Recipe saved'), __('The recipe was successfully saved'));
    }

    public function updatePicture()
    {
        $this->validate(['picture' => ['integer', 'mimes:jpg,jpeg,png']]);
    }

    public function deletePicture()
    {
        $this->authorize('update', $this->recipe);

        if (!is_null($this->recipe->picture)) {
            $this->deleteImage('recipes/' . $this->recipe->picture);
            $this->recipe->picture = null;
            $this->recipe->save();
        }

        $this->picture = false;
    }
}
