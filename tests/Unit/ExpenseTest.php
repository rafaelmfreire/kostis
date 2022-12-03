<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_get_formatted_date()
    {
        $user = User::factory()->make();
        $category = Category::factory()->make();

        $expense = Expense::factory()->make([
            'user_id' => $user->id,
            'date' => Carbon::parse('2022-11-18'),
            'category_id' => $category->id
        ]);

        $date = $expense->formatted_date;

        $this->assertEquals('18/11/2022', $date);
    }

    /** @test */
    public function can_get_cost_in_brazilian_real()
    {
        $user = User::factory()->make();
        $category = Category::factory()->make();

        $expense = Expense::factory()->make([
            'user_id' => $user->id,
            'cost' => 112500,
            'category_id' => $category->id
        ]);

        $cost = $expense->formatted_cost;

        $this->assertEquals('1.125,00', $cost);
    }
}
