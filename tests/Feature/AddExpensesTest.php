<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AddExpensesTest extends TestCase
{
    use DatabaseMigrations;

    // public function from($url)
    // {
    //     session()->setPreviousUrl(url($url));
    //     return $this;
    // }

    /** @test */
    public function users_can_view_the_add_expense_form()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/expenses/create');

        $response->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_add_expense_form()
    {
        $response = $this->get('/expenses/create');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function adding_a_valid_expense()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/expenses', [
            'date' => '2022-11-18',
            'cost' => '1.125,00',
            'description' => 'Example',
            'observation' => 'some observation',
        ]);

        tap(Expense::first(), function ($expense) use ($response, $user) {
            $response->assertRedirect('/expenses');

            $this->assertTrue($expense->user->is($user));

            $this->assertEquals(Carbon::parse('2022-11-18'), $expense->date);
            $this->assertEquals(112500, $expense->cost);
            $this->assertEquals('Example', $expense->description);
            $this->assertEquals('some observation', $expense->observation);
        });
    }

    /** @test */
    public function guests_cannot_add_new_expenses()
    {
        $response = $this->post('/expenses', [
            'date' => '2022-11-18',
            'cost' => '1.125,00',
            'description' => 'Example',
            'observation' => 'some observation',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertEquals(0, Expense::count());
    }

    /** @test */
    public function date_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/expenses/create')->post('/expenses', [
            'date' => '',
            'cost' => '1.125,00',
            'description' => 'Example',
            'observation' => 'some observation',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/expenses/create');
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Expense::count());
    }
}
