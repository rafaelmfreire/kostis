<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Source::factory()->create(['name' => 'Nubank', 'color' => 'purple']);
        Source::factory()->create(['name' => 'Banco do Brasil', 'color' => 'yellow']);
        Source::factory()->create(['name' => 'Pix', 'color' => 'blue']);
        Source::factory()->create(['name' => 'Transferência', 'color' => 'red']);
        Source::factory()->create(['name' => 'Dinheiro', 'color' => 'green']);
    }
}
