<?php

namespace Tests\Feature;

use App\Models\Revenue;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
