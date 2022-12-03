<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->create([ 'name' => 'Lazer', 'color' => 'fuchsia' ]);
        Category::factory()->create([ 'name' => 'Profissional', 'color' => 'teal' ]);
        Category::factory()->create([ 'name' => 'Pessoal', 'color' => 'yellow' ]);
        Category::factory()->create([ 'name' => 'Taxa', 'color' => 'pink' ]);
        Category::factory()->create([ 'name' => 'Casa', 'color' => 'lime' ]);
        Category::factory()->create([ 'name' => 'Carro', 'color' => 'red' ]);
        Category::factory()->create([ 'name' => 'Vestuário', 'color' => 'rose' ]);
        Category::factory()->create([ 'name' => 'Presente', 'color' => 'amber' ]);
        Category::factory()->create([ 'name' => 'Saúde', 'color' => 'green' ]);
        Category::factory()->create([ 'name' => 'Mercado', 'color' => 'purple' ]);
        Category::factory()->create([ 'name' => 'Pet', 'color' => 'emerald' ]);
        Category::factory()->create([ 'name' => 'Nico', 'color' => 'cyan' ]);
        Category::factory()->create([ 'name' => 'Alimentação', 'color' => 'orange' ]);
        Category::factory()->create([ 'name' => 'Educação', 'color' => 'blue' ]);
        Category::factory()->create([ 'name' => 'Viagem', 'color' => 'sky' ]);
        Category::factory()->create([ 'name' => 'Carol', 'color' => 'indigo' ]);
        Category::factory()->create([ 'name' => 'Rojânia', 'color' => 'violet' ]);
    }
}
