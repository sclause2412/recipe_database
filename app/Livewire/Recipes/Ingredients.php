<?php

namespace App\Livewire\Recipes;

use App\Actions\Livewire\CleanupInput;
use App\Models\Ingredient;
use App\Models\RecipeIngredient;
use App\Models\Unit;
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

        $ingredients = $this->recipe->ingredients()->orderBy('sort');

        $ingredients = $ingredients->get()->all();

        return view('livewire.recipes.ingredients', ['ingredients' => $ingredients]);
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
            $ingredient = new RecipeIngredient();
            $ingredient->recipe_id = $this->recipe->id;
        }

        $ingredient2 = Ingredient::where('id', $this->ingredient)?->first();

        if (is_null($ingredient2)) {
            $ingredient2 = new Ingredient();
            if (preg_match('/^(.+) \((.+)\)$/', $this->ingredient, $matches)) {
                $ingredient2->name = $matches[1];
                $ingredient2->info = $matches[2];
            } else {
                $ingredient2->name = $this->ingredient;
            }
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

        if ($ingredient->sort <= 1) {
            return;
        }

        $row = $this->recipe->ingredients()->where('sort', $ingredient->sort - 1)->first();
        if (is_null($row)) {
            $ingredient->sort--;
            $ingredient->save();
        } elseif ($row->group == $ingredient->group) {
            $row->sort++;
            $row->save();
            $ingredient->sort--;
            $ingredient->save();
        } else {
            $rows = $this->recipe->ingredients()->where('group', $row->group)->orderBy('sort')->get();
            $first = true;
            foreach ($rows as $row) {
                if ($first) {
                    $ingredient->sort = $row->sort;
                    $ingredient->save();
                    $first = false;
                }
                $row->sort++;
                $row->save();
            }

        }

        $this->_reorder();
    }

    public function stepDown(RecipeIngredient $ingredient)
    {
        $this->authorize('update', $this->recipe);

        if ($ingredient->sort >= $this->recipe->ingredients->count()) {
            return;
        }

        $row = $this->recipe->ingredients()->where('sort', $ingredient->sort + 1)->first();
        if (is_null($row)) {
            $ingredient->sort++;
            $ingredient->save();
        } elseif ($row->group == $ingredient->group) {
            $row->sort--;
            $row->save();
            $ingredient->sort++;
            $ingredient->save();
        } else {
            $rows = $this->recipe->ingredients()->where('group', $ingredient->group)->orderBy('sort')->get();
            $first = true;
            foreach ($rows as $row2) {
                if ($first) {
                    $row->sort = $row2->sort;
                    $row->save();
                    $first = false;
                }
                $row2->sort++;
                $row2->save();
            }
        }

        $this->_reorder();
    }

    private function _reorder()
    {
        $ingredients = $this->recipe->ingredients()->orderBy('sort')->get();

        $groups = $ingredients->pluck('group')->toArray();

        if (in_array(null, $groups)) {
            $groups = array_merge([null], $groups);
        }
        $groups = array_values(array_unique($groups));

        $sort = 1;

        foreach ($groups as $group) {
            $ingredients = $this->recipe->ingredients()->where('group', $group)->orderBy('sort')->get();

            foreach ($ingredients as $ingredient) {
                $ingredient->sort = $sort++;
                $ingredient->save();
            }
        }
    }


}
