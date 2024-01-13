<?php

namespace App\Livewire\Units;

use App\Actions\Livewire\CleanupInput;
use App\Models\Unit;
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
    public $unit = null;
    public $name = null;
    public $fraction = false;

    public function render()
    {


        $units = Unit::orderBy('name');
        return view('livewire.units.index', ['units' => $units->paginate(10, ['*'], 'page')]);
    }

    public function editUnit(Unit $unit)
    {

        if (!check_write('recipe'))
            abort(403);

        $this->rid = $unit->id;
        $this->unit = $unit->unit;
        $this->name = $unit->name;
        $this->fraction = $unit->fraction;
    }

    public function saveUnit()
    {

        if (!check_write('recipe'))
            abort(403);

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
            $unit = new Unit;
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
        if (!check_write('recipe'))
            abort(403);

        $unit->delete();
    }


}
