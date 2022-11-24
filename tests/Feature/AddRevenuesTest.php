<?php

namespace Tests\Feature;

use App\Models\Revenue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AddRevenuesTest extends TestCase
{
    use DatabaseMigrations;

    private function validParams($overrides = [])
    {
        return array_merge([
            'date' => '2022-11-18',
            'income' => '1125.00',
            'description' => 'Example',
            'observation' => 'some observation',
        ], $overrides);
    }

    /** @test */
    public function users_can_view_the_add_revenue_form()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/revenues/create');

        $response->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_add_revenue_form()
    {
        $response = $this->get('/revenues/create');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function add_a_valid_revenue()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/revenues', [
            'date' => '2022-11-18',
            'income' => '1125.00',
            'description' => 'Example',
            'observation' => 'some observation',
        ]);

        tap(Revenue::first(), function ($revenue) use ($response, $user) {
            $response->assertRedirect('/revenues');

            $this->assertTrue($revenue->user->is($user));

            $this->assertEquals(Carbon::parse('2022-11-18'), $revenue->date);
            $this->assertEquals(112500, $revenue->income);
            $this->assertEquals('Example', $revenue->description);
            $this->assertEquals('some observation', $revenue->observation);
        });
    }

    /** @test */
    public function guests_cannot_add_new_revenues()
    {
        $response = $this->post('/revenues', $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertEquals(0, Revenue::count());
    }

    /** @test */
    public function date_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/revenues/create')->post('/revenues', $this->validParams([
            'date' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/revenues/create');
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Revenue::count());
    }

    /** @test */
    public function date_must_be_a_valid_date()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/revenues/create')->post('/revenues', $this->validParams([
            'date' => 'not-a-date',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/revenues/create');
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Revenue::count());
    }

    /** @test */
    public function income_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/revenues/create')->post('/revenues', $this->validParams([
            'income' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/revenues/create');
        $response->assertSessionHasErrors('income');
        $this->assertEquals(0, Revenue::count());
    }

    /** @test */
    public function income_must_be_numeric()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/revenues/create')->post('/revenues', $this->validParams([
            'income' => 'not-a-number',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/revenues/create');
        $response->assertSessionHasErrors('income');
        $this->assertEquals(0, Revenue::count());
    }

    /** @test */
    public function description_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/revenues/create')->post('/revenues', $this->validParams([
            'description' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/revenues/create');
        $response->assertSessionHasErrors('description');
        $this->assertEquals(0, Revenue::count());
    }

    /** @test */
    public function observation_is_optional()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/revenues', $this->validParams([
            'observation' => '',
        ]));

        tap(Revenue::first(), function ($revenue) use ($response, $user) {
            $response->assertRedirect('/revenues');

            $this->assertTrue($revenue->user->is($user));

            $this->assertNull($revenue->observation);
        });
    }
}
