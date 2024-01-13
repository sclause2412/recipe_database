<?php

namespace App\Livewire\Ingredients;

use App\Actions\Livewire\CleanupInput;
use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class Index extends Component
{
    use WithPagination;
    use CleanupInput;
    use WireUiActions;

    public $rid = null;
    public $name = null;

    public function render()
    {

        $ingredients = Ingredient::orderBy('name');
        return view('livewire.ingredients.index', ['ingredients' => $ingredients->paginate(10, ['*'], 'page')]);
    }

    public function editIngredient(Ingredient $ingredient)
    {

        if (!check_write('recipe'))
            abort(403);

        $this->rid = $ingredient->id;
        $this->name = $ingredient->name;
    }

    public function saveIngredient()
    {

        if (!check_write('recipe'))
            abort(403);

        $this->rid = $this->cleanInput($this->rid);
        $this->name = $this->cleanInput($this->name);

        $ingredient = null;
        if (!is_null($this->rid)) {
            $ingredient = Ingredient::where('id', $this->rid)->first();
        }

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);


        if (is_null($ingredient)) {
            $ingredient = new Ingredient;
        }

        $ingredient->name = $this->name;
        $ingredient->save();

        $this->rid = null;
        $this->name = null;

        $this->notification()->success(__('Ingredient saved'), __('The ingredient was successfully saved'));

    }

    public function deleteIngredient(Ingredient $ingredient)
    {

        if (!check_write('recipe'))
            abort(403);

        $ingredient->delete();
    }


}
