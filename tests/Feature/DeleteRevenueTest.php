<?php

namespace Tests\Feature;

use App\Models\Revenue;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DeleteRevenueTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function users_can_delete_their_revenues()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $revenue = Revenue::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals(1, Revenue::count());

        $month = $revenue->date->format('Y-m');

        $month = $revenue->date->format('Y-m');

        $response = $this->actingAs($user)->delete("/revenues/{$revenue->id}");

        $this->assertEquals(0, Revenue::count());

        // The user is redirected to the index page
        // with the same month filter applied
        $response->assertRedirect('/revenues?month='.$month);
    }

    /** @test */
    public function users_can_delete_only_their_revenues()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $revenue = Revenue::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $this->assertEquals(1, Revenue::count());

        $response = $this->actingAs($user)->delete("/revenues/{$revenue->id}");

        $response->assertStatus(404);
        $this->assertEquals(1, Revenue::count());
    }

    /** @test */
    public function guests_cannot_delete_revenues()
    {
        $user = User::factory()->create();
        $revenue = Revenue::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals(1, Revenue::count());

        $response = $this->delete("/revenues/{$revenue->id}");

        $this->assertEquals(1, Revenue::count());
        $response->assertRedirect('/login');
    }
}
