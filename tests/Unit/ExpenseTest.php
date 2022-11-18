<?php

namespace Tests\Unit;

use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    /** @test */
    public function can_get_formatted_date()
    {
        $user = User::factory()->make();

        $expense = Expense::factory()->make([
            'user_id' => $user->id,
            'date' => Carbon::parse('2022-11-18'),
        ]);

        $date = $expense->formatted_date;

        $this->assertEquals('18/11/2022', $date);
    }
}
