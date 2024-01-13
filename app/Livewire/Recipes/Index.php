<?php

namespace App\Livewire\Recipes;

use App\Models\Recipe;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

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
        $this->sort = in_array($field, ['name', 'category', 'description', 'cooked', 'active']) ? $field : 'name';
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

        if (check_read('recipe')) {
            $recipes = Recipe::query();
        } else {
            $recipes = Recipe::where('active', true);
        }

        $recipes = $recipes->search(['name', 'category.name', 'description'], $this->search);

        switch ($this->sort) {
            case null:
                break;
            case 'active':
            case 'cooked':
                $recipes = $recipes->orderBy($this->sort, $this->dir == 'asc' ? 'desc' : 'asc');
                break;
            default:
                $recipes = $recipes->orderBy($this->sort, $this->dir);
                break;
        }

        $recipes = $recipes->orderBy('name', 'asc');

        return view('livewire.recipes.index', ['recipes' => $recipes->paginate(10)]);
    }
}
