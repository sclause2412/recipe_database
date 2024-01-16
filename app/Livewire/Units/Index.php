<?php

namespace App\Livewire\Units;

use App\Actions\Livewire\CleanupInput;
use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class Index extends Component
{
    use WithPagination;
    use CleanupInput;
    use WireUiActions;

    public $rid = null;
    public $unit = null;
    public $name = null;
    public $fraction = false;

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
        $this->sort = in_array($field, ['name', 'unit', 'fraction', 'recipes']) ? $field : 'name';
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

        $units = Unit::query();

        $units = $units->search(['name', 'unit'], $this->search);

        switch ($this->sort) {
            case null:
                break;
            case 'fraction':
                $units = $units->orderBy($this->sort, $this->dir == 'asc' ? 'desc' : 'asc');
                break;
            case 'recipes':
                break;
            default:
                $units = $units->orderBy($this->sort, $this->dir);
                break;
        }

        $units = $units->orderBy('name', 'asc');

        if ($this->sort == 'recipes') {
            if ($this->dir == 'asc')
                $units = $units->get()->sortBy('recipes');
            else
                $units = $units->get()->sortByDesc('recipes');
        }

        return view('livewire.units.index', ['units' => $units->paginate(10, ['*'], 'page')]);
    }

    public function editUnit(Unit $unit)
    {

        if (!check_write('recipe')) {
            abort(403);
        }

        $this->rid = $unit->id;
        $this->unit = $unit->unit;
        $this->name = $unit->name;
        $this->fraction = $unit->fraction;
    }

    public function saveUnit()
    {

        if (!check_write('recipe')) {
            abort(403);
        }

        $this->rid = $this->cleanInput($this->rid);
        $this->unit = $this->cleanInput($this->unit);
        $this->name = $this->cleanInput($this->name);
        $this->fraction = $this->cleanInput($this->fraction);

        $unit = null;
        if (!is_null($this->rid)) {
            $unit = Unit::where('id', $this->rid)->first();
        }

        $this->validate([
            'unit' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'fraction' => ['boolean'],
        ]);


        if (is_null($unit)) {
            $unit = new Unit();
        }

        $unit->unit = $this->unit;
        $unit->name = $this->name;
        $unit->fraction = $this->fraction;
        $unit->save();

        $this->rid = null;
        $this->unit = null;
        $this->name = null;
        $this->fraction = false;

        $this->notification()->success(__('Unit saved'), __('The unit was successfully saved'));

    }

    public function deleteUnit(Unit $unit)
    {
        if (!check_write('recipe')) {
            abort(403);
        }

        $unit->delete();
    }


}
