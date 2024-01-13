<?php

namespace App\Http\Controllers;

use App\Models\Category;

class WelcomeController extends Controller
{
    public function welcome()
    {
        $data = [];
        foreach (Category::orderBy('name')->get() as $category) {
            $name = $category->name;
            $recipes = $category->recipes()->where('active', true)->inRandomOrder()->take(5)->get()->pluck('name', 'slug');
            if (count($recipes) > 0) {
                array_push($data, ['name' => $name, 'recipes' => $recipes]);
            }
        }

        return view('welcome', ['data' => $data]);
    }
}
