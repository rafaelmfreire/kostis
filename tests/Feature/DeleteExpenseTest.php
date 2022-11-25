<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeleteExpenseTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function users_can_delete_their_expenses()
    {
        $user = User::factory()->create();
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals(1, Expense::count());

        $response = $this->actingAs($user)->delete("/expenses/{$expense->id}");

        $this->assertEquals(0, Expense::count());
        $response->assertRedirect('/expenses');
    }

    /** @test */
    public function users_can_delete_only_their_expenses()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $expense = Expense::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $this->assertEquals(1, Expense::count());

        $response = $this->actingAs($user)->delete("/expenses/{$expense->id}");

        $response->assertStatus(404);
        $this->assertEquals(1, Expense::count());
    }

    /** @test */
    public function guests_cannot_delete_expenses()
    {
        $user = User::factory()->create();
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals(1, Expense::count());

        $response = $this->delete("/expenses/{$expense->id}");

        $this->assertEquals(1, Expense::count());
        $response->assertRedirect('/login');
    }
}
