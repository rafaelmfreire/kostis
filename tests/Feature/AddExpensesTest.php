<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Source;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class AddExpensesTest extends TestCase
{
    use DatabaseMigrations;

    private function validParams($overrides = [])
    {
        return array_merge([
            'date' => '2022-11-18',
            'cost' => '1125.00',
            'description' => 'Example',
            'observation' => 'some observation',
        ], $overrides);
    }

    /** @test */
    public function users_can_view_the_add_expense_form()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/expenses/create');

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Expenses/Create')
        );
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
        $category = Category::factory()->create();
        $source = Source::factory()->create();

        $response = $this->actingAs($user)->post('/expenses', [
            'date' => '2022-11-18',
            'cost' => '1125.00',
            'description' => 'Example',
            'observation' => 'some observation',
            'category_id' => $category->id,
            'source_id' => $source->id
        ]);

        tap(Expense::first(), function ($expense) use ($response, $user, $category, $source) {
            $response->assertRedirect('/expenses');

            $this->assertTrue($expense->fresh()->user->is($user));

            $this->assertEquals(Carbon::parse('2022-11-18'), $expense->fresh()->date);
            $this->assertEquals(112500, $expense->fresh()->cost);
            $this->assertEquals('Example', $expense->fresh()->description);
            $this->assertEquals('some observation', $expense->fresh()->observation);
            $this->assertEquals($category->name, $expense->fresh()->category->name);
            $this->assertEquals($source->name, $expense->fresh()->source->name);
        });
    }

    /** @test */
    public function guests_cannot_add_new_expenses()
    {
        $response = $this->post('/expenses', $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertEquals(0, Expense::count());
    }

    /** @test */
    public function date_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/expenses/create')->post('/expenses', $this->validParams([
            'date' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/expenses/create');
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Expense::count());
    }

    /** @test */
    public function date_must_be_a_valid_date()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/expenses/create')->post('/expenses', $this->validParams([
            'date' => 'not-a-date',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/expenses/create');
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Expense::count());
    }

    /** @test */
    public function cost_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/expenses/create')->post('/expenses', $this->validParams([
            'cost' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/expenses/create');
        $response->assertSessionHasErrors('cost');
        $this->assertEquals(0, Expense::count());
    }

    /** @test */
    public function cost_must_be_numeric()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/expenses/create')->post('/expenses', $this->validParams([
            'cost' => 'not-a-number',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/expenses/create');
        $response->assertSessionHasErrors('cost');
        $this->assertEquals(0, Expense::count());
    }

    /** @test */
    public function description_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/expenses/create')->post('/expenses', $this->validParams([
            'description' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/expenses/create');
        $response->assertSessionHasErrors('description');
        $this->assertEquals(0, Expense::count());
    }

    /** @test */
    public function observation_is_optional()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        // TODO: refactor to "hide" the setup of category since
        // it's not an essential part of this test
        $category = Category::factory()->create();
        $source = Source::factory()->create();

        $response = $this->actingAs($user)->post('/expenses', $this->validParams([
            'observation' => '',
            'category_id' => $category->id,
            'source_id' => $source->id,
        ]));

        tap(Expense::first(), function ($expense) use ($response, $user) {
            $response->assertRedirect('/expenses');

            $this->assertTrue($expense->user->is($user));

            $this->assertNull($expense->observation);
        });
    }

    /** @test */
    public function category_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/expenses/create')->post('/expenses', $this->validParams([
            'category_id' => null
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/expenses/create');
        $response->assertSessionHasErrors('category_id');
        $this->assertEquals(0, Expense::count());
    }

    /** @test */
    public function source_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/expenses/create')->post('/expenses', $this->validParams([
            'source_id' => null
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/expenses/create');
        $response->assertSessionHasErrors('source_id');
        $this->assertEquals(0, Expense::count());
    }

    // TODO: user_can_add_an_expense_and_be_redirected_to_form_again
}
