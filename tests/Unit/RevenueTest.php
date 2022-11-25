<?php

namespace Tests\Unit;

use App\Models\Revenue;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class RevenueTest extends TestCase
{
    /** @test */
    public function can_get_formatted_date()
    {
        $user = User::factory()->make();

        $revenue = Revenue::factory()->make([
            'user_id' => $user->id,
            'date' => Carbon::parse('2022-11-18'),
        ]);

        $date = $revenue->formatted_date;

        $this->assertEquals('18/11/2022', $date);
    }

    /** @test */
    public function can_get_income_in_brazilian_real()
    {
        $user = User::factory()->make();

        $revenue = Revenue::factory()->make([
            'user_id' => $user->id,
            'income' => 112500,
        ]);

        $income = $revenue->formatted_income;

        $this->assertEquals('1.125,00', $income);
    }
}
