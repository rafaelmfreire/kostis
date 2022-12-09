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
    public function can_get_formatted_bought_at()
    {
        $user = User::factory()->make();
        $category = Category::factory()->make();

        $expense = Expense::factory()->make([
            'user_id' => $user->id,
            'bought_at' => Carbon::parse('2022-11-18'),
            'category_id' => $category->id,
        ]);

        $bought_at = $expense->formatted_bought_at;

        $this->assertEquals('18/11/2022', $bought_at);
    }

    /** @test */
    public function can_add_installments()
    {
        $user = User::factory()->create();
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'installments_quantity' => 3,
        ]);

        $this->assertEquals(0, $expense->installments()->count());
        
        $expense->addInstallments(Carbon::parse('2012-12'));

        $this->assertEquals(3, $expense->installments()->count());
    }
    // TODO: Move to InstallmentTest
    /** @test */
    // public function can_get_formatted_paid_at()
    // {
    //     $user = User::factory()->make();
    //     $category = Category::factory()->make();

    //     $expense = Expense::factory()->make([
    //         'user_id' => $user->id,
    //         'paid_at' => Carbon::parse('2022-11-18'),
    //         'category_id' => $category->id,
    //     ]);

    //     $paid_at = $expense->formatted_paid_at;

    //     $this->assertEquals('18/11/2022', $paid_at);
    // }

    /** @test */
    public function can_get_cost_in_brazilian_real()
    {
        $user = User::factory()->make();
        $category = Category::factory()->make();

        $expense = Expense::factory()->make([
            'user_id' => $user->id,
            'cost' => 112500,
            'category_id' => $category->id,
        ]);

        $cost = $expense->formatted_cost;

        $this->assertEquals('1.125,00', $cost);
    }
}
