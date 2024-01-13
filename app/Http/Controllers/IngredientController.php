<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Ingredient::class, 'ingredient');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('ingredients.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ingredient $ingredient)
    {

        return view('ingredients.show', ['ingredient' => $ingredient]);
    }
}
