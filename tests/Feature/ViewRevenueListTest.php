<?php

namespace Tests\Feature;

use App\Models\Revenue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ViewRevenueListTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_cannot_view_a_users_list_of_revenues()
    {
        $response = $this->get('/revenues');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_can_only_view_a_list_of_their_revenues()
    {
        //Arrange
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $revenueA = Revenue::factory()->create(['user_id' => $user->id]);
        $revenueB = Revenue::factory()->create(['user_id' => $otherUser->id]);
        $revenueC = Revenue::factory()->create(['user_id' => $user->id]);

        //Act
        $response = $this->actingAs($user)->get('/revenues');

        //Assert
        $response->assertStatus(200);

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Revenues/Index')
            ->has('revenues', 2, fn (AssertableInertia $page) => $page
                ->has('formatted_date')
                ->has('formatted_income')
                ->has('description')
                ->has('observation')
                ->etc()
            )
            ->where('revenues', function ($value) use ($revenueA, $revenueB, $revenueC) {
                return $value->contains($revenueA->toArray()) &&
                       $value->contains($revenueC->toArray()) &&
                       $value->doesntContain($revenueB->toArray());
            })
        );
    }

    /** @test */
    public function user_can_view_the_total_income_by_month()
    {
        $user = User::factory()->create();

        $revenueA = Revenue::factory()->create([
            'user_id' => $user->id,
            'income' => '1200',
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        $revenueB = Revenue::factory()->create([
            'user_id' => $user->id,
            'income' => '2500',
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        $revenueC = Revenue::factory()->create([
            'user_id' => $user->id,
            'income' => '3400',
            'date' => Carbon::parse('+1 month')->format('Y-m-d'),
        ]);

        $response = $this->actingAs($user)->get('/revenues?month='.Carbon::now()->format('Y-m'));

        $response->assertStatus(200);

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Revenues/Index')
            ->has('stats.total_income')
            ->where('stats.total_income', '37,00')
        );
    }

    /** @test */
    public function user_can_view_the_most_valuable_income_by_month()
    {
        $user = User::factory()->create();

        $revenueA = Revenue::factory()->create([
            'user_id' => $user->id,
            'income' => '1200',
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        $revenueB = Revenue::factory()->create([
            'user_id' => $user->id,
            'income' => '3400',
            'date' => Carbon::parse('+1 month')->format('Y-m-d'),
        ]);

        $revenueC = Revenue::factory()->create([
            'user_id' => $user->id,
            'income' => '2500',
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($user)->get('/revenues?month='.Carbon::now()->format('Y-m'));

        $response->assertStatus(200);

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Revenues/Index')
            ->has('stats.most_valuable')
            ->where('stats.most_valuable', '25,00')
        );
    }

    /** @test */
    public function user_can_view_the_quantity_of_revenues_by_month()
    {
        $user = User::factory()->create();

        $revenueA = Revenue::factory()->create([
            'user_id' => $user->id,
            'income' => '1200',
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        $revenueB = Revenue::factory()->create([
            'user_id' => $user->id,
            'income' => '3400',
            'date' => Carbon::parse('+1 month')->format('Y-m-d'),
        ]);

        $revenueC = Revenue::factory()->create([
            'user_id' => $user->id,
            'income' => '2500',
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($user)->get('/revenues?month='.Carbon::now()->format('Y-m'));

        $response->assertStatus(200);

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Revenues/Index')
            ->has('stats.revenues_quantity')
            ->where('stats.revenues_quantity', 2)
        );
    }

    /** @test */
    public function user_can_view_the_average_income_by_month()
    {
        $user = User::factory()->create();

        $revenueA = Revenue::factory()->create([
            'user_id' => $user->id,
            'income' => '1200',
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        $revenueB = Revenue::factory()->create([
            'user_id' => $user->id,
            'income' => '3400',
            'date' => Carbon::parse('+1 month')->format('Y-m-d'),
        ]);

        $revenueC = Revenue::factory()->create([
            'user_id' => $user->id,
            'income' => '2500',
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($user)->get('/revenues?month='.Carbon::now()->format('Y-m'));

        $response->assertStatus(200);

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Revenues/Index')
            ->has('stats.average')
            ->where('stats.average', '18,50')
        );
    }
}
