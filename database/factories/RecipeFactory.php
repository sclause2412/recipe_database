<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\Team;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;
use Faker;

class RecipeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recipe::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->words(rand(1, 3), true)),
            'category_id' => Category::inRandomOrder()->first()?->id,
            'cooked' => rand(0, 1) == 0,
            'source' => $this->faker->url(),
            'portions' => rand(1, 6),
            'time' => rand(5, 180),
            'description' => $this->faker->realText(100),
            'active' => rand(0, 10) != 0,
        ];
    }
}
