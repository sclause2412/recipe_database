<?php

namespace App\Http\Controllers;

use App\Models\Recipe;

class RecipeController extends Controller
{
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

        $portions = request()->get('portions', $recipe->portions ?? 1);
        if ($portions < 1) {
            $portions = 1;
        }
        $temp = request()->get('temp', 'C');

        return view('recipes.show', [
            'recipe' => $recipe,
            'ingredients' => $ingredients,
            'steps' => $steps,
            'comments' => $comments,
            'ingredient_list' => $ingredient_list,
            'portions' => $portions,
            'temp' => $temp
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
