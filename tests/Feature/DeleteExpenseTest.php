<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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
        $expense->addInstallments(Carbon::now()->format('Y-m'));

        $this->assertEquals(1, Expense::count());

        $month = $expense->installments()->first()->paid_at->format('Y-m');

        $response = $this->actingAs($user)->delete("/expenses/{$expense->id}");

        $this->assertEquals(0, Expense::count());

        // The user is redirected to the index page
        // with the same month filter applied
        $response->assertRedirect('/expenses?month='.$month);
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
