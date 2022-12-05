<?php

namespace Tests\Feature;

use App\Models\Revenue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class EditRevenueTest extends TestCase
{
    use DatabaseMigrations;

    public function oldAttributes($overrides = [])
    {
        return array_merge([
            'date' => '2022-12-01',
            'income' => '32.50',
            'description' => 'New description',
            'observation' => 'new observation',
        ], $overrides);
    }

    public function validParams($overrides = [])
    {
        return array_merge([
            'date' => '2022-12-01',
            'income' => '32.50',
            'description' => 'New description',
            'observation' => 'new observation',
        ], $overrides);
    }

    /** @test */
    public function users_can_view_the_edit_form_for_their_own_revenues()
    {
        $this->withoutExceptionHandling();

        //Arrange
        $user = User::factory()->create();
        $revenue = Revenue::factory()->create(['user_id' => $user->id]);
        
        //Act
        $response = $this->actingAs($user)->get("/revenues/{$revenue->id}/edit");

        //Assert
        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Revenues/Edit')
        );
    }

    /** @test */
    public function users_cannot_view_the_edit_form_for_other_revenues()
    {
        //Arrange
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $revenue = Revenue::factory()->create(['user_id' => $otherUser->id]);
        
        //Act
        $response = $this->actingAs($user)->get("/revenues/{$revenue->id}/edit");

        //Assert
        $response->assertStatus(404);
    }
    
    /** @test */
    public function users_see_a_404_when_attempting_to_view_the_edit_form_for_an_revenue_that_does_not_exist()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get("/revenues/9999/edit");
    
        $response->assertStatus(404);
    }

    /** @test */
    public function guests_are_asked_to_login_when_attempting_to_view_the_edit_form_for_any_revenue()
    {
        $otherUser = User::factory()->create();
        $revenue = Revenue::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->get("/revenues/{$revenue->id}/edit");

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function guests_are_asked_to_login_when_attempting_to_view_the_edit_form_for_an_revenue_that_does_not_exist()
    {
        $response = $this->get("/revenues/9999/edit");
    
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function users_can_edit_their_own_revenues()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $revenue = Revenue::factory()->create([
            'user_id' => $user->id,
            'date' => '2022-12-03',
            'income' => 2500,
            'description' => 'Sample description',
            'observation' => 'sample observation',
        ]);

        $response = $this->actingAs($user)->patch("/revenues/{$revenue->id}", [
            'date' => '2023-01-02',
            'income' => '32.50',
            'description' => 'New description',
            'observation' => 'new observation',
        ]);

        $response->assertRedirect('/revenues');
        tap($revenue->fresh(), function ($revenue) {
            $this->assertEquals(Carbon::parse('2023-01-02'), $revenue->date);
            $this->assertEquals(3250, $revenue->income);
            $this->assertEquals('New description', $revenue->description);
            $this->assertEquals('new observation', $revenue->observation);
        });
    }

    /** @test */
    public function guests_cannot_edit_revenues()
    {
        $user = User::factory()->create();
        $revenue = Revenue::factory()->create($this->oldAttributes([
            'user_id' => $user->id,
        ]));

        $response = $this->patch("/revenues/{$revenue->id}", $this->validParams());

        $response->assertRedirect('/login');

        tap($revenue->fresh(), function ($revenue) {
            $this->assertEquals(Carbon::parse($this->oldAttributes()['date']), $revenue->date);
            $this->assertEquals($this->oldAttributes()['income'], $revenue->income);
            $this->assertEquals($this->oldAttributes()['description'], $revenue->description);
            $this->assertEquals($this->oldAttributes()['observation'], $revenue->observation);
        });
    }

    /** @test */
    public function income_is_required()
    {
        $user = User::factory()->create();
        $revenue = Revenue::factory()->create([
            'user_id' => $user->id,
            'income' => 1200,
        ]);

        $response = $this->actingAs($user)->from("/revenues/{$revenue->id}/edit")->patch("/revenues/{$revenue->id}", $this->validParams([
            'income' => '',
        ]));

        $response->assertRedirect("/revenues/{$revenue->id}/edit");
        $response->assertSessionHasErrors('income');

        tap($revenue->fresh(), function ($revenue) {
            $this->assertEquals(1200, $revenue->income);
        });
    }

    /** @test */
    public function income_must_be_numeric()
    {
        $user = User::factory()->create();
        $revenue = Revenue::factory()->create([
            'user_id' => $user->id,
            'income' => 1200,
        ]);

        $response = $this->actingAs($user)->from("/revenues/{$revenue->id}/edit")->patch("/revenues/{$revenue->id}", $this->validParams([
            'income' => 'not-a-number',
        ]));

        $response->assertRedirect("/revenues/{$revenue->id}/edit");
        $response->assertSessionHasErrors('income');

        tap($revenue->fresh(), function ($revenue) {
            $this->assertEquals(1200, $revenue->income);
        });
    }

    /** @test */
    public function date_is_required()
    {
        $user = User::factory()->create();

        $revenue = Revenue::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::parse('2022-12-04'),
        ]);

        $response = $this->actingAs($user)->from("/revenues/{$revenue->id}/edit")->patch("/revenues/{$revenue->id}", $this->validParams([
            'date' => '',
        ]));

        $response->assertRedirect("/revenues/{$revenue->id}/edit");
        $response->assertSessionHasErrors('date');

        tap($revenue->fresh(), function ($revenue) {
            $this->assertEquals(Carbon::parse('2022-12-04'), $revenue->date);
        });
    }

    /** @test */
    public function date_must_be_a_valid_date()
    {
        $user = User::factory()->create();
        $revenue = Revenue::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::parse('2022-12-04'),
        ]);

        $response = $this->actingAs($user)->from("/revenues/{$revenue->id}/edit")->patch("/revenues/{$revenue->id}", $this->validParams([
            'date' => 'not-a-valid-date',
        ]));

        $response->assertRedirect("/revenues/{$revenue->id}/edit");
        $response->assertSessionHasErrors('date');

        tap($revenue->fresh(), function ($revenue) {
            $this->assertEquals(Carbon::parse('2022-12-04'), $revenue->date);
        });
    }

    /** @test */
    public function description_is_required()
    {
        $user = User::factory()->create();
        $revenue = Revenue::factory()->create([
            'user_id' => $user->id,
            'description' => 'Old description',
        ]);

        $response = $this->actingAs($user)->from("/revenues/{$revenue->id}/edit")->patch("/revenues/{$revenue->id}", $this->validParams([
            'description' => '',
        ]));

        $response->assertRedirect("/revenues/{$revenue->id}/edit");
        $response->assertSessionHasErrors('description');

        tap($revenue->fresh(), function ($revenue) {
            $this->assertEquals('Old description', $revenue->description);
        });
    }

    /** @test */
    public function observation_is_optional()
    {
        $user = User::factory()->create();
        $revenue = Revenue::factory()->create([
            'user_id' => $user->id,
            'observation' => 'Old observation',
        ]);

        $response = $this->actingAs($user)->from("/revenues/{$revenue->id}/edit")->patch("/revenues/{$revenue->id}", $this->validParams([
            'observation' => '',
        ]));

        $response->assertRedirect("/revenues/");

        tap($revenue->fresh(), function ($revenue) {
            $this->assertNull($revenue->observation);
        });
    }
}
