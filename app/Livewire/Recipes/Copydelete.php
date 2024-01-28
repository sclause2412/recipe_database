<?php

namespace App\Livewire\Recipes;

use App\Actions\Files\HasImage;
use App\Models\Recipe;
use App\Models\RecipeComment;
use App\Models\RecipeIngredient;
use App\Models\RecipeStep;
use App\Overrides\ConfirmsPasswords;
use Livewire\Component;

class Copydelete extends Component
{
    use ConfirmsPasswords;
    use HasImage;

    public $recipe;

    public function mount($recipe)
    {
        $this->recipe = $recipe;
    }

    public function render()
    {
        return view('livewire.recipes.copydelete');
    }

    public function deleteRecipe()
    {
        $this->authorize('delete', $this->recipe);

        $recipe = $this->recipe;

        $recipe->ingredients()->delete();
        $recipe->steps()->delete();
        $recipe->comments()->delete();

        if (!is_null($recipe->picture)) {
            $this->deleteImage('recipes/' . $recipe->picture);
        }


        $recipe->delete();
        return redirect()->route('recipes.index');
    }

    public function copyRecipe()
    {
        $this->authorize('create', Recipe::class);

        $source = $this->recipe;

        $recipe = new Recipe();
        $recipe->name = __('Copy of ') . $source->name;
        $recipe->category_id = $source->category_id;
        $recipe->cooked = $source->cooked;
        $recipe->source = $source->source;
        $recipe->portions = $source->portions;
        $recipe->time = $source->time;
        $recipe->description = $source->description;
        $recipe->active = false;
        if (!is_null($source->picture)) {
            $id = md5(uniqid());
            if ($this->copyImage('recipes/' . $source->picture, 'recipes/' . $id)) {
                $recipe->picture = $id;
            }
        }

        $recipe->save();

        foreach ($source->ingredients as $row) {
            $ent = new RecipeIngredient();
            $ent->recipe_id = $recipe->id;
            $ent->ingredient_id = $row->ingredient_id;
            $ent->group = $row->group;
            $ent->amount = $row->amount;
            $ent->unit_id = $row->unit_id;
            $ent->save();
        }

        foreach ($source->steps as $row) {
            $ent = new RecipeStep();
            $ent->recipe_id = $recipe->id;
            $ent->step = $row->step;
            $ent->text = $row->text;
            $ent->save();
        }

        foreach ($source->comments as $row) {
            $ent = new RecipeComment();
            $ent->recipe_id = $recipe->id;
            $ent->step = $row->step;
            $ent->text = $row->text;
            $ent->save();
        }

        return redirect()->route('recipes.edit', $recipe);
    }
}
