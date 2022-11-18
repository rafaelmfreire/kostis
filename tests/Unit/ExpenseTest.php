<?php

namespace Tests\Unit;

use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_get_formatted_date()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::parse('2022-11-18')
        ]);

        $date = $expense->formatted_date;

        $this->assertEquals('18/11/2022', $date);
    }
}
