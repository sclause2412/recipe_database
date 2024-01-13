<?php

namespace App\Livewire\Categories;

use App\Actions\Livewire\CleanupInput;
use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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
        $categories = Category::orderBy('name');
        return view('livewire.categories.index', ['categories' => $categories->paginate(10, ['*'], 'page')]);
    }

    public function editCategory(Category $category)
    {
        if (!check_write('recipe'))
            abort(403);

        $this->rid = $category->id;
        $this->name = $category->name;
    }

    public function saveCategory()
    {
        if (!check_write('recipe'))
            abort(403);

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
            $category = new Category;
        }

        $category->name = $this->name;
        $category->save();

        $this->rid = null;
        $this->name = null;

        $this->notification()->success(__('Category saved'), __('The category was successfully saved'));

    }

    public function deleteCategory(Category $category)
    {
        if (!check_write('recipe'))
            abort(403);

        $category->delete();
    }


}
