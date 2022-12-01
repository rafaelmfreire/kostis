<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Source;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ViewExpenseListTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_cannot_view_a_users_list_of_expenses()
    {
        $response = $this->get('/expenses');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_can_only_view_a_list_of_their_expenses()
    {
        //Arrange
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $category = Category::factory()->create();
        $source = Source::factory()->create();

        $expenseA = Expense::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'source_id' => $source->id,
        ]);
        $expenseB = Expense::factory()->create(['user_id' => $otherUser->id, 'category_id' => $category->id, 'source_id' => $source->id]);
        $expenseC = Expense::factory()->create(['user_id' => $user->id, 'category_id' => $category->id, 'source_id' => $source->id]);

        //Act
        $response = $this->actingAs($user)->get('/expenses');

        //Assert
        $response->assertStatus(200);

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Expenses/Index')
            ->has('expenses', 2, fn (AssertableInertia $page) => $page
                ->has('formatted_date')
                ->has('formatted_cost')
                ->has('description')
                ->has('observation')
                ->has('user_id')
                ->has('category_name')
                ->has('category_color')
                ->has('source_name')
                ->has('source_color')
                ->etc()
            )
            ->where('expenses', function ($value) use ($expenseA, $expenseB, $expenseC) {
                return 
                    collect($value[0])->diff($expenseA->toArray())->count() == 0 &&
                    collect($value[1])->diff($expenseC->toArray())->count() == 0 &&
                    collect($value[0])->diff($expenseB->toArray())->count() > 0 &&
                    collect($value[1])->diff($expenseB->toArray())->count() > 0;
            })
        );
    }

    /** @test */
    public function user_can_view_their_expenses_filtered_by_month()
    {
        //Arrange
        $user = User::factory()->create();
        $expenseA = Expense::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::now(),
        ]);
        $expenseB = Expense::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::parse('+1 month'),
        ]);
        $expenseC = Expense::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::now(),
        ]);

        //Act
        $response = $this->actingAs($user)->call('GET', '/expenses', ['month' => '2022-11']);

        //Assert
        $response->assertStatus(200);

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Expenses/Index')
            ->has('expenses', 2, fn (AssertableInertia $page) => $page
                ->has('formatted_date')
                ->has('formatted_cost')
                ->has('description')
                ->has('observation')
                ->etc()
            )
        );
    }
}
