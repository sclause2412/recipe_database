<?php

namespace App\Livewire\Categories;

use App\Actions\Livewire\CleanupInput;
use App\Models\Category;
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

        $categories = Category::query();

        $categories = $categories->search(['name'], $this->search);

        switch ($this->sort) {
            case null:
                break;
            case 'recipes':
                break;
            default:
                $categories = $categories->orderBy($this->sort, $this->dir);
                break;
        }

        $categories = $categories->orderBy('name', 'asc');

        if ($this->sort == 'recipes') {
            if ($this->dir == 'asc')
                $categories = $categories->get()->sortBy('recipes');
            else
                $categories = $categories->get()->sortByDesc('recipes');
        }

        return view('livewire.categories.index', ['categories' => $categories->paginate(10, ['*'], 'page')]);
    }

    public function editCategory(Category $category)
    {
        if (!check_write('recipe')) {
            abort(403);
        }

        $this->rid = $category->id;
        $this->name = $category->name;
    }

    public function saveCategory()
    {
        if (!check_write('recipe')) {
            abort(403);
        }

        $this->rid = $this->cleanInput($this->rid);
        $this->name = $this->cleanInput($this->name);

        $category = null;
        if (!is_null($this->rid)) {
            $category = Category::where('id', $this->rid)->first();
        }

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);


        if (is_null($category)) {
            $category = new Category();
        }

        $category->name = $this->name;
        $category->save();

        $this->rid = null;
        $this->name = null;

        $this->notification()->success(__('Category saved'), __('The category was successfully saved'));

    }

    public function deleteCategory(Category $category)
    {
        if (!check_write('recipe')) {
            abort(403);
        }

        $category->delete();
    }


}
