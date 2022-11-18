<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\Assert;
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
    public function user_can_view_a_list_of_their_expenses()
    {
        //Arrange
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $expenseA = Expense::factory()->create(['user_id' => $user->id]);
        $expenseB = Expense::factory()->create(['user_id' => $otherUser->id]);
        $expenseC = Expense::factory()->create(['user_id' => $user->id]);

        //Act
        $response = $this->actingAs($user)->get('/expenses');

        //Assert
        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Expenses/Index')
            ->where('expenses', function ($value) use ($expenseA, $expenseB, $expenseC) {
                return $value->contains($expenseA->toArray()) &&
                       $value->contains($expenseC->toArray()) &&
                       $value->doesntContain($expenseB->toArray());
            })
        );
    }
}
