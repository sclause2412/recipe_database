<?php

namespace App\Livewire\Recipes;

use App\Actions\Livewire\CleanupInput;
use App\Models\RecipeStep;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class Steps extends Component
{
    use WithPagination;
    use CleanupInput;
    use WireUiActions;

    public $recipe;
    public $editMode = false;
    public $rid;
    public $text;

    public function render()
    {
        $this->authorize('view', $this->recipe);

        $ingredients = [];
        foreach ($this->recipe->ingredients as $ingredient) {
            $ingredients[$ingredient->reference] = [
                'amount' => $ingredient->amount,
                'unit' => $ingredient->unit,
                'name' => $ingredient->ingredient?->name,
            ];
        }

        $steps_db = $this->recipe->steps()->orderBy('step')->get();
        $steps = [];
        foreach ($steps_db as $step) {
            $step_entry = [
                'id' => $step->id,
                'text' => text_code_format($step->text, $ingredients, ['preview' => true]),
            ];
            array_push($steps, (object) $step_entry);
        }

        return view('livewire.recipes.steps', ['steps' => $steps]);
    }

    public function newStep(): void
    {
        $this->editMode = false;
        $this->rid = null;
    }

    public function editStep(RecipeStep $step)
    {
        $this->authorize('update', $this->recipe);

        $this->editMode = true;
        $this->rid = $step->id;
        $this->text = $step->text;
    }

    public function saveStep()
    {
        $this->authorize('update', $this->recipe);

        $this->rid = $this->cleanInput($this->rid);
        $this->text = $this->cleanInput($this->text);

        $step = null;
        if (!is_null($this->rid)) {
            $step = RecipeStep::where('id', $this->rid)->where('recipe_id', $this->recipe->id)->first();
        }

        $this->validate([
            'text' => ['required', 'string'],
        ]);

        if (is_null($step)) {
            $step = new RecipeStep();
            $step->recipe_id = $this->recipe->id;
            $step->step = $this->recipe->steps->count() + 1;
        }

        $step->text = $this->text;
        $step->save();

        $this->editMode = false;
        $this->rid = null;
        $this->text = null;

        $this->notification()->success(__('Step saved'), __('The step was successfully saved'));
    }

    public function deleteStep(RecipeStep $step)
    {
        $this->authorize('update', $this->recipe);

        foreach ($this->recipe->steps()->where('step', '>', $step->step)->get() as $row) {
            $row->step--;
            $row->save();
        }

        if ($step->id == $this->rid) {
            $this->editMode = false;
            $this->rid = null;
        }

        $step->delete();
    }

    public function stepUp(RecipeStep $step)
    {
        $this->authorize('update', $this->recipe);

        if ($step->step <= 1) {
            return;
        }

        $row = $this->recipe->steps()->where('step', $step->step - 1)->first();
        if (!is_null($row)) {
            $row->step++;
            $row->save();
        }

        $step->step--;
        $step->save();
    }

    public function stepDown(RecipeStep $step)
    {
        $this->authorize('update', $this->recipe);

        if ($step->step >= $this->recipe->steps->count()) {
            return;
        }

        $row = $this->recipe->steps()->where('step', $step->step + 1)->first();
        if (!is_null($row)) {
            $row->step--;
            $row->save();
        }

        $step->step++;
        $step->save();
    }
}
