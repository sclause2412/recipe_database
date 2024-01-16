<?php

namespace App\Livewire\Ingredients;

use App\Actions\Livewire\CleanupInput;
use App\Models\Ingredient;
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

    public $search = '';
    public $sort;
    public $dir;

    protected $queryString = ['sort', 'dir'];

    public function sortBy($field)
    {
        $field = strtolower($field);
        if ($field === $this->sort) {
            $this->dir = $this->dir == 'asc' ? 'desc' : 'asc';
        } else {
            $this->dir = 'asc';
        }
        $this->sort = in_array($field, ['name', 'recipes']) ? $field : 'name';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (!in_array($this->dir, [null, 'asc', 'desc'])) {
            $this->dir = 'asc';
        }

        $ingredients = Ingredient::query();

        $ingredients = $ingredients->search(['name'], $this->search);

        switch ($this->sort) {
            case null:
                break;
            case 'recipes':
                break;
            default:
                $ingredients = $ingredients->orderBy($this->sort, $this->dir);
                break;
        }

        $ingredients = $ingredients->orderBy('name', 'asc');

        if ($this->sort == 'recipes') {
            if ($this->dir == 'asc')
                $ingredients = $ingredients->get()->sortBy('recipes');
            else
                $ingredients = $ingredients->get()->sortByDesc('recipes');
        }

        return view('livewire.ingredients.index', ['ingredients' => $ingredients->paginate(10, ['*'], 'page')]);
    }

    public function editIngredient(Ingredient $ingredient)
    {

        if (!check_write('recipe')) {
            abort(403);
        }

        $this->rid = $ingredient->id;
        $this->name = $ingredient->name;
    }

    public function saveIngredient()
    {

        if (!check_write('recipe')) {
            abort(403);
        }

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
            $ingredient = new Ingredient();
        }

        $ingredient->name = $this->name;
        $ingredient->save();

        $this->rid = null;
        $this->name = null;

        $this->notification()->success(__('Ingredient saved'), __('The ingredient was successfully saved'));

    }

    public function deleteIngredient(Ingredient $ingredient)
    {

        if (!check_write('recipe')) {
            abort(403);
        }

        $ingredient->delete();
    }


}
