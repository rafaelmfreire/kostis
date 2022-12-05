<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Source;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'bought_at' => Carbon::now(),
            'paid_at' => Carbon::now(),
            'cost' => fake()->randomNumber(4, true),
            'description' => fake()->word(),
            'observation' => fake()->sentence(),
            'category_id' => function () {
                return Category::factory()->create()->id;
            },
            'source_id' => function () {
                return Source::factory()->create()->id;
            },
        ];
    }
}
