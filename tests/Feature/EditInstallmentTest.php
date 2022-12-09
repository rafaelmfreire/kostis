<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class EditInstallmentTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function users_can_view_the_edit_form_for_the_installments_of_their_own_expenses()
    {
        $this->withoutExceptionHandling();

        //Arrange
        $user = User::factory()->create();
        $expense = Expense::factory()->create(['user_id' => $user->id]);
        $installments = $expense->addInstallments('2022-12');
        
        //Act
        $response = $this->actingAs($user)->get("/expenses/{$expense->id}/installments/{$installments->first()->id}/edit");

        //Assert
        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Installments/Edit')
        );
    }
}
