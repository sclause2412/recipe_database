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
        $ingredients = $recipe->ingredients->sortBy('sort');
        $steps = $recipe->steps->sortBy('step');
        $comments = $recipe->comments->sortBy('step');

        $ingredient_list = $ingredients->pluck('ingredient.name', 'reference');

        $picture = $this->getImage('recipes/' . $recipe->picture);

        $updated_at = $recipe->updated_at->timezone('Europe/Vienna')->isoFormat('LLLL');
        $updated_by = User::where('id', $recipe->updated_by)->first()?->fullname;

        return view('recipes.show', [
            'recipe' => $recipe,
            'ingredients' => $ingredients,
            'steps' => $steps,
            'comments' => $comments,
            'ingredient_list' => $ingredient_list,
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
