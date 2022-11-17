<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ViewExpenseListingTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_view_an_expense_listing()
    {
        $this->withoutExceptionHandling();

        //Arrange
        $expense = Expense::create([
            'date' => Carbon::parse('2022-11-15'),
            'cost' => '2500',
            'description' => 'Walmart',
            'observation' => 'food and higiene',
        ]);

        //Act
        $response = $this->get('/expenses');

        //Assert
        $response->assertStatus(200);
        //see the expense details
        // $response->assertSee('15/11/2022');
        // $response->assertSee('R$ 25,00');
        $response->assertSee('Walmart');
        $response->assertSee('food and higiene');
    }
}
