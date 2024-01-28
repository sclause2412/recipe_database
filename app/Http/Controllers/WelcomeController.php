<?php

namespace App\Http\Controllers;

use App\Actions\Files\HasImage;
use App\Models\Category;

class WelcomeController extends Controller
{
    use HasImage;

    public function welcome()
    {
        $data = [];
        foreach (Category::orderBy('name')->get() as $category) {
            $name = $category->name;
            $recipes = $category->recipes()->where('active', true)->inRandomOrder()->take(4)->get();
            if (count($recipes) > 0) {
                array_push($data, ['name' => $name, 'recipes' => $recipes]);
            }
        }

        return view('welcome', ['data' => $data, 'controller' => $this]);
    }
}
