<?php

namespace App\Livewire\Recipes;

use App\Actions\Livewire\CleanupInput;
use App\Constants\LevelConstants;
use App\Constants\RightConstants;
use App\Models\Ingredient;
use App\Models\RecipeComment;
use App\Models\RecipeIngredient;
use App\Models\RecipeStep;
use App\Models\Right;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class Ingredients extends Component
{
    use WithPagination;
    use CleanupInput;
    use WireUiActions;

    public $recipe;
    public $rid = null;
    public $ingredient = null;
    public $group = null;
    public $amount = null;
    public $unit = null;

    public function render()
    {
        $this->authorize('view', $this->recipe);

        $ingredients = $this->recipe->ingredients()->orderBy('group')->orderBy('sort');

        return view('livewire.recipes.ingredients', ['ingredients' => $ingredients->get()]);
    }

    public function editIngredient(RecipeIngredient $ingredient)
    {
        $this->authorize('update', $this->recipe);

        $this->rid = $ingredient->id;
        $this->ingredient = $ingredient->ingredient?->id;
        $this->group = $ingredient->group;
        $this->amount = $ingredient->amount;
        $this->unit = $ingredient->unit?->id;
    }

    public function saveIngredient()
    {
        $this->authorize('update', $this->recipe);

        $this->rid = $this->cleanInput($this->rid);
        $this->ingredient = $this->cleanInput($this->ingredient);
        $this->group = $this->cleanInput($this->group);
        $this->amount = $this->cleanInput($this->amount);
        $this->unit = $this->cleanInput($this->unit);

        $ingredient = null;
        if (!is_null($this->rid)) {
            $ingredient = RecipeIngredient::where('id', $this->rid)->where('recipe_id', $this->recipe->id)->first();
        }

        $this->validate([
            'ingredient' => ['required'],
            'group' => ['nullable', 'string'],
            'amount' => ['nullable', 'numeric'],
            'unit' => ['nullable']
        ]);

        if (is_null($ingredient)) {
            $ingredient = new RecipeIngredient;
            $ingredient->recipe_id = $this->recipe->id;
        }

        $ingredient2 = Ingredient::where('id', $this->ingredient)?->first();

        if (is_null($ingredient2)) {
            $ingredient2 = new Ingredient;
            $ingredient2->name = $this->ingredient;
            $ingredient2->save();
        }

        $unit = Unit::where('id', $this->unit)?->first();

        $ingredient->ingredient_id = $ingredient2->id;
        $ingredient->group = $this->group;
        $ingredient->amount = $this->amount;
        $ingredient->unit_id = $unit?->id;

        $ingredient->save();

        $this->_reorder();

        $this->rid = null;
        $this->ingredient = null;
        $this->group = null;
        $this->amount = null;
        $this->unit = null;

        $this->notification()->success(__('Ingredient saved'), __('The ingredient was successfully saved'));

    }

    public function deleteIngredient(RecipeIngredient $ingredient)
    {
        $this->authorize('update', $this->recipe);

        $ingredient->delete();
        $this->_reorder();
    }

    public function stepUp(RecipeIngredient $ingredient)
    {
        $this->authorize('update', $this->recipe);

        if ($ingredient->sort <= 1)
            return;

        $row = $this->recipe->ingredients()->where('sort', $ingredient->sort - 1)->first();
        if (!is_null($row)) {
            $row->sort++;
            $row->save();
        }

        $ingredient->sort--;
        $ingredient->save();

        $this->_reorder();
    }

    public function stepDown(RecipeIngredient $ingredient)
    {
        $this->authorize('update', $this->recipe);

        $ingredient->sort = 999;
        $ingredient->save();

        $this->_reorder();
    }

    private function _reorder()
    {
        $sort = 1;
        $ingredients = $this->recipe->ingredients()->orderBy('group')->orderBy('sort')->get();
        foreach ($ingredients as $ingredient) {
            $ingredient->sort = $sort++;
            $ingredient->save();
        }
    }


}
