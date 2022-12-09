<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Source;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class EditExpenseTest extends TestCase
{
    use DatabaseMigrations;

    public function oldAttributes($overrides = [])
    {
        return array_merge([
            'bought_at' => '2022-12-01',
            'installments_quantity' => 1,
            'cost' => '32.50',
            'description' => 'New description',
            'observation' => 'new observation',
        ], $overrides);
    }

    public function validParams($overrides = [])
    {
        return array_merge([
            'bought_at' => '2022-12-01',
            'installments_quantity' => 1,
            'cost' => '32.50',
            'description' => 'New description',
            'observation' => 'new observation',
        ], $overrides);
    }

    /** @test */
    public function users_can_view_the_edit_form_for_their_own_expenses()
    {
        $this->withoutExceptionHandling();

        //Arrange
        $user = User::factory()->create();
        $expense = Expense::factory()->create(['user_id' => $user->id]);
        
        //Act
        $response = $this->actingAs($user)->get("/expenses/{$expense->id}/edit");

        //Assert
        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Expenses/Edit')
        );
    }

    /** @test */
    public function users_cannot_view_the_edit_form_for_other_expenses()
    {
        //Arrange
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $expense = Expense::factory()->create(['user_id' => $otherUser->id]);
        
        //Act
        $response = $this->actingAs($user)->get("/expenses/{$expense->id}/edit");

        //Assert
        $response->assertStatus(404);
    }
    
    /** @test */
    public function users_see_a_404_when_attempting_to_view_the_edit_form_for_an_expense_that_does_not_exist()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get("/expenses/9999/edit");
    
        $response->assertStatus(404);
    }

    /** @test */
    public function guests_are_asked_to_login_when_attempting_to_view_the_edit_form_for_any_expense()
    {
        $otherUser = User::factory()->create();
        $expense = Expense::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->get("/expenses/{$expense->id}/edit");

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
    
    /** @test */
    public function guests_are_asked_to_login_when_attempting_to_view_the_edit_form_for_an_expense_that_does_not_exist()
    {
        $response = $this->get("/expenses/9999/edit");
    
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function users_can_edit_their_own_expenses()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'bought_at' => '2022-11-30',
            'description' => 'Sample description',
            'observation' => 'sample observation',
            'category_id' => Category::factory()->create()->id,
            'source_id' => Source::factory()->create()->id,
        ]);

        $newCategory = Category::factory()->create();
        $newSource = Source::factory()->create();

        $response = $this->actingAs($user)->patch("/expenses/{$expense->id}", [
            'bought_at' => '2022-12-01',
            'category_id' => $newCategory->id,
            'source_id' => $newSource->id,
            'description' => 'New description',
            'observation' => 'new observation',
        ]);

        $response->assertRedirect('/expenses');
        tap($expense->fresh(), function ($expense) use ($newCategory, $newSource) {
            $this->assertEquals(Carbon::parse('2022-12-01'), $expense->bought_at);
            $this->assertEquals($newCategory->id, $expense->category_id);
            $this->assertEquals($newSource->id, $expense->source_id);
            $this->assertEquals('New description', $expense->description);
            $this->assertEquals('new observation', $expense->observation);
        });
    }

    /** @test */
    public function guests_cannot_edit_expenses()
    {
        $user = User::factory()->create();
        $oldCategory = Category::factory()->create();
        $oldSource = Source::factory()->create();
        $expense = Expense::factory()->create($this->oldAttributes([
            'user_id' => $user->id,
            'category_id' => $oldCategory->id,
            'source_id' => $oldSource->id,
        ]));

        $response = $this->patch("/expenses/{$expense->id}", $this->validParams());

        $response->assertRedirect('/login');

        tap($expense->fresh(), function ($expense) use ($oldCategory, $oldSource) {
            $this->assertEquals(Carbon::parse($this->oldAttributes()['bought_at']), $expense->bought_at);
            $this->assertEquals($oldCategory->id, $expense->category_id);
            $this->assertEquals($oldSource->id, $expense->source_id);
            $this->assertEquals($this->oldAttributes()['description'], $expense->description);
            $this->assertEquals($this->oldAttributes()['observation'], $expense->observation);
        });
    }

    /** @test */
    // public function cost_is_required()
    // {
    //     $user = User::factory()->create();
    //     $category = Category::factory()->create();
    //     $source = Source::factory()->create();
    //     $expense = Expense::factory()->create([
    //         'user_id' => $user->id,
    //         'category_id' => $category->id,
    //         'source_id' => $source->id,
    //         'cost' => 1200,
    //     ]);

    //     $response = $this->actingAs($user)->from("/expenses/{$expense->id}/edit")->patch("/expenses/{$expense->id}", $this->validParams([
    //         'cost' => '',
    //     ]));

    //     $response->assertRedirect("/expenses/{$expense->id}/edit");
    //     $response->assertSessionHasErrors('cost');

    //     tap($expense->fresh(), function ($expense) {
    //         $this->assertEquals(1200, $expense->cost);
    //     });
    // }

    // /** @test */
    // public function cost_must_be_numeric()
    // {
    //     $user = User::factory()->create();
    //     $category = Category::factory()->create();
    //     $source = Source::factory()->create();
    //     $expense = Expense::factory()->create([
    //         'user_id' => $user->id,
    //         'category_id' => $category->id,
    //         'source_id' => $source->id,
    //         'cost' => 1200,
    //     ]);

    //     $response = $this->actingAs($user)->from("/expenses/{$expense->id}/edit")->patch("/expenses/{$expense->id}", $this->validParams([
    //         'cost' => 'not-a-number',
    //     ]));

    //     $response->assertRedirect("/expenses/{$expense->id}/edit");
    //     $response->assertSessionHasErrors('cost');

    //     tap($expense->fresh(), function ($expense) {
    //         $this->assertEquals(1200, $expense->cost);
    //     });
    // }

    /** @test */
    public function bought_at_is_required()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $source = Source::factory()->create();

        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'source_id' => $source->id,
            'bought_at' => Carbon::parse('2022-12-04'),
        ]);

        $response = $this->actingAs($user)->from("/expenses/{$expense->id}/edit")->patch("/expenses/{$expense->id}", $this->validParams([
            'bought_at' => '',
            'category_id' => $category->id,
            'source_id' => $source->id,
        ]));

        $response->assertRedirect("/expenses/{$expense->id}/edit");
        $response->assertSessionHasErrors('bought_at');

        tap($expense->fresh(), function ($expense) {
            $this->assertEquals(Carbon::parse('2022-12-04'), $expense->bought_at);
        });
    }

    /** @test */
    public function bought_at_must_be_a_valid_date()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $source = Source::factory()->create();
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'source_id' => $source->id,
            'bought_at' => Carbon::parse('2022-12-04'),
        ]);

        $response = $this->actingAs($user)->from("/expenses/{$expense->id}/edit")->patch("/expenses/{$expense->id}", $this->validParams([
            'bought_at' => 'not-a-valid-date',
        ]));

        $response->assertRedirect("/expenses/{$expense->id}/edit");
        $response->assertSessionHasErrors('bought_at');

        tap($expense->fresh(), function ($expense) {
            $this->assertEquals(Carbon::parse('2022-12-04'), $expense->bought_at);
        });
    }

    // TODO: analyse paid_at on edit expense
    /** @test */
    // public function paid_at_is_required()
    // {
    //     $user = User::factory()->create();
    //     $category = Category::factory()->create();
    //     $source = Source::factory()->create();
    //     $expense = Expense::factory()->create([
    //         'user_id' => $user->id,
    //         'category_id' => $category->id,
    //         'source_id' => $source->id,
    //         'paid_at' => Carbon::parse('2022-12-04'),
    //     ]);

    //     $response = $this->actingAs($user)->from("/expenses/{$expense->id}/edit")->patch("/expenses/{$expense->id}", $this->validParams([
    //         'paid_at' => '',
    //         'category_id' => $category->id,
    //         'source_id' => $source->id,
    //     ]));

    //     $response->assertRedirect("/expenses/{$expense->id}/edit");
    //     $response->assertSessionHasErrors('paid_at');

    //     tap($expense->fresh(), function ($expense) {
    //         $this->assertEquals(Carbon::parse('2022-12-04'), $expense->paid_at);
    //     });
    // }

    /** @test */
    // public function paid_at_must_be_a_valid_date()
    // {
    //     $user = User::factory()->create();
    //     $category = Category::factory()->create();
    //     $source = Source::factory()->create();
    //     $expense = Expense::factory()->create([
    //         'user_id' => $user->id,
    //         'category_id' => $category->id,
    //         'source_id' => $source->id,
    //         'paid_at' => Carbon::parse('2022-12-04'),
    //     ]);

    //     $response = $this->actingAs($user)->from("/expenses/{$expense->id}/edit")->patch("/expenses/{$expense->id}", $this->validParams([
    //         'paid_at' => 'not-a-valid-date',
    //     ]));

    //     $response->assertRedirect("/expenses/{$expense->id}/edit");
    //     $response->assertSessionHasErrors('paid_at');

    //     tap($expense->fresh(), function ($expense) {
    //         $this->assertEquals(Carbon::parse('2022-12-04'), $expense->paid_at);
    //     });
    // }

    /** @test */
    public function description_is_required()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $source = Source::factory()->create();
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'source_id' => $source->id,
            'description' => 'Old description',
        ]);

        $category = Category::factory()->create();
        $source = Source::factory()->create();
        $response = $this->actingAs($user)->from("/expenses/{$expense->id}/edit")->patch("/expenses/{$expense->id}", $this->validParams([
            'description' => '',
            'category_id' => $category->id,
            'source_id' => $source->id,
        ]));

        $response->assertRedirect("/expenses/{$expense->id}/edit");
        $response->assertSessionHasErrors('description');

        tap($expense->fresh(), function ($expense) {
            $this->assertEquals('Old description', $expense->description);
        });
    }

    /** @test */
    public function observation_is_optional()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $source = Source::factory()->create();
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'source_id' => $source->id,
            'observation' => 'Old observation',
        ]);

        $response = $this->actingAs($user)->from("/expenses/{$expense->id}/edit")->patch("/expenses/{$expense->id}", $this->validParams([
            'observation' => '',
            'category_id' => $category->id,
            'source_id' => $source->id,
        ]));

        $response->assertRedirect("/expenses/");

        tap($expense->fresh(), function ($expense) {
            $this->assertNull($expense->observation);
        });
    }

    // TODO: user can view the installments of the editing expense
}
