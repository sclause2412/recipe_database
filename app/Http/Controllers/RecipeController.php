<?php

namespace App\Http\Controllers;

use App\Actions\Files\HasImage;
use App\Models\Recipe;
use App\Models\User;

class RecipeController extends Controller
{
    use HasImage;

    public function __construct()
    {
        $this->authorizeResource(Recipe::class, 'recipe');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('recipes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('recipes.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        $temp = request()->get('temp', 'C');
        if ($temp != 'F') {
            $temp = 'C';
        }
        $portions = request()->get('portions', 0);
        if ($portions <= 0) {
            $portions = $recipe->portions ?? 1;
        }
        $portions = floor($portions * 8) / 8;
        if ($portions < 0.125) {
            $portions = 0.125;
        }
        if ($portions > 10000) {
            $portions = 10000;
        }

        $factor = $portions / ($recipe->portions ?? 1);

        $ingredients_db = $recipe->ingredients->sortBy('sort');
        $ingredients = [];
        $ingredient_list = [];
        foreach ($ingredients_db as $ingredient) {
            $amount = ($ingredient->amount ?? 0) * ($ingredient->fix ? 1 : $factor);
            $ingredient_entry = [
                'group' => $ingredient->group,
                'amount' => $amount == 0 ? null : calculate_number($amount, $ingredient->unit?->fraction ?? false),
                'unit' => calculate_unit($ingredient->unit?->unit, $amount),
                'approximately' => $ingredient->approximately,
                'reference' => $ingredient->reference,
                'name' => calculate_unit($ingredient->ingredient?->name, is_null($ingredient->unit) ? $amount : null),
                'info' => $ingredient->ingredient?->info,
            ];
            $ingredient_list[$ingredient->reference] = [
                'amount' => $amount,
                'unit' => $ingredient->unit,
                'name' => $ingredient->ingredient?->name,
            ];
            array_push($ingredients, (object) $ingredient_entry);
        }

        $steps_db = $recipe->steps->sortBy('step');
        $steps = [];
        foreach ($steps_db as $step) {
            $text = text_code_format($step->text, $ingredient_list, ['factor' => $factor, 'temp' => $temp]);
            array_push($steps, $text);
        }

        $comments_db = $recipe->comments->sortBy('step');
        $comments = [];
        foreach ($comments_db as $comment) {
            $text = text_code_format($comment->text, $ingredient_list, ['factor' => $factor, 'temp' => $temp]);
            array_push($comments, $text);
        }

        $picture = $this->getImage('recipes/' . $recipe->picture);

        $updated_at = $recipe->updated_at->timezone('Europe/Vienna')->isoFormat('LLLL');
        $updated_by = User::where('id', $recipe->updated_by)->first()?->fullname;

        return view('recipes.show', [
            'recipe' => $recipe,
            'portions' => $portions,
            'temp' => $temp,
            'ingredients' => $ingredients,
            'steps' => $steps,
            'comments' => $comments,
            'picture' => $picture,
            'updated_at' => $updated_at,
            'updated_by' => $updated_by,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipe $recipe)
    {
        return view('recipes.edit', ['recipe' => $recipe]);
    }
}
