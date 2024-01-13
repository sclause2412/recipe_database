<?php

namespace App\Livewire\Recipes;

use App\Actions\Livewire\CleanupInput;
use App\Constants\LevelConstants;
use App\Constants\RightConstants;
use App\Models\RecipeComment;
use App\Models\RecipeStep;
use App\Models\Right;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class Steps extends Component
{
    use WithPagination;
    use CleanupInput;
    use WireUiActions;

    public $recipe;
    public $rid = null;
    public $text = null;

    public function render()
    {
        $this->authorize('view', $this->recipe);

        $steps = $this->recipe->steps()->orderBy('step');
        $ingredients = $this->recipe->ingredients->pluck('ingredient.name', 'reference');

        return view('livewire.recipes.steps', ['steps' => $steps->get(), 'ingredients' => $ingredients]);
    }

    public function editStep(RecipeStep $step)
    {
        $this->authorize('update', $this->recipe);

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
            $step = new RecipeStep;
            $step->recipe_id = $this->recipe->id;
            $step->step = $this->recipe->steps->count() + 1;
        }

        $step->text = $this->text;
        $step->save();

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

        $step->delete();
    }

    public function stepUp(RecipeStep $step)
    {
        $this->authorize('update', $this->recipe);

        if ($step->step <= 1)
            return;

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

        if ($step->step >= $this->recipe->steps->count())
            return;

        $row = $this->recipe->steps()->where('step', $step->step + 1)->first();
        if (!is_null($row)) {
            $row->step--;
            $row->save();
        }

        $step->step++;
        $step->save();
    }




}
