<?php

namespace Database\Seeders;

use App\Models\TypeOfIndustry;
use Illuminate\Database\Seeder;

class TypeOfIndustriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            TypeOfIndustry::query()->firstOrCreate([
                'name' => 'Test ' . $i,
            ]);
        }
    }
}
