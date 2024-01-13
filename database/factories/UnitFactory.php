<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UnitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Unit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->word()),
            'unit' => $this->faker->regexify('[A-Za-z][a-z]{0,2}'),
            'fraction' => rand(0, 1) == 0,
        ];
    }
}
