<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeComment;
use App\Models\RecipeIngredient;
use App\Models\RecipeStep;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Recipe::factory(100)->create();


        $ings = Ingredient::all()->pluck('id');
        $units = Unit::all()->pluck('id');
        foreach (Recipe::all() as $recipe) {
            for ($i = 0; $i < rand(1, 20); $i++) {
                $ing = new RecipeIngredient();
                $ing->recipe_id = $recipe->id;
                $ing->ingredient_id = fake()->randomElement($ings);
                $ing->group = rand(0, 10) == 0 ? fake()->randomElement(['Group 1', 'Group 2', 'Group 3']) : null;
                $ing->amount = rand(0, 10000) / 100;
                if ($ing->amount == 0) {
                    $ing->amount = null;
                }
                $ing->unit_id = fake()->randomElement($units);
                $ing->save();
            }

            $sort = 1;
            $ingredients = $recipe->ingredients()->orderBy('group')->orderBy('sort')->get();
            foreach ($ingredients as $ingredient) {
                $ingredient->sort = $sort++;
                $ingredient->save();
            }

            for ($i = 0; $i < rand(1, 20); $i++) {
                $ing = new RecipeStep();
                $ing->recipe_id = $recipe->id;
                $ing->step = $i + 1;
                $ing->text = fake()->realText(200);
                $ing->save();
            }

            for ($i = 0; $i < rand(0, 3); $i++) {
                $ing = new RecipeComment();
                $ing->recipe_id = $recipe->id;
                $ing->step = $i + 1;
                $ing->text = fake()->realText(200);
                $ing->save();
            }
        }

    }
}
