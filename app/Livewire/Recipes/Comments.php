<?php

namespace App\Livewire\Recipes;

use App\Actions\Livewire\CleanupInput;
use App\Models\RecipeComment;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class Comments extends Component
{
    use WithPagination;
    use CleanupInput;
    use WireUiActions;

    public $recipe;
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

        $comments_db = $this->recipe->comments()->orderBy('step')->get();
        $comments = [];
        foreach ($comments_db as $comment) {
            $comment_entry = [
                'id' => $comment->id,
                'text' => text_code_format($comment->text, $ingredients, ['preview' => true]),
            ];
            array_push($comments, (object) $comment_entry);
        }

        return view('livewire.recipes.comments', ['comments' => $comments]);
    }

    public function editComment(RecipeComment $comment)
    {
        $this->authorize('update', $this->recipe);

        $this->rid = $comment->id;
        $this->text = $comment->text;
    }

    public function saveComment()
    {
        $this->authorize('update', $this->recipe);

        $this->rid = $this->cleanInput($this->rid);
        $this->text = $this->cleanInput($this->text);

        $comment = null;
        if (!is_null($this->rid)) {
            $comment = RecipeComment::where('id', $this->rid)->where('recipe_id', $this->recipe->id)->first();
        }

        $this->validate([
            'text' => ['required', 'string'],
        ]);

        if (is_null($comment)) {
            $comment = new RecipeComment();
            $comment->recipe_id = $this->recipe->id;
            $comment->step = $this->recipe->comments->count() + 1;
        }

        $comment->text = $this->text;
        $comment->save();

        $this->rid = null;
        $this->text = null;

        $this->notification()->success(__('Comment saved'), __('The comment was successfully saved'));
    }

    public function deleteComment(RecipeComment $comment)
    {
        $this->authorize('update', $this->recipe);

        foreach ($this->recipe->comments()->where('step', '>', $comment->step)->get() as $row) {
            $row->step--;
            $row->save();
        }

        $comment->delete();
    }

    public function stepUp(RecipeComment $comment)
    {
        $this->authorize('update', $this->recipe);

        if ($comment->step <= 1) {
            return;
        }

        $row = $this->recipe->comments()->where('step', $comment->step - 1)->first();
        if (!is_null($row)) {
            $row->step++;
            $row->save();
        }

        $comment->step--;
        $comment->save();
    }

    public function stepDown(RecipeComment $comment)
    {
        $this->authorize('update', $this->recipe);

        if ($comment->step >= $this->recipe->comments->count()) {
            return;
        }

        $row = $this->recipe->comments()->where('step', $comment->step + 1)->first();
        if (!is_null($row)) {
            $row->step--;
            $row->save();
        }

        $comment->step++;
        $comment->save();
    }
}
