<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            'Type 1',
            'Type 2',
            'Type 3',
        ];

        foreach ($states as $state) {
            DocumentType::firstOrCreate(['name' => $state]);
        }
    }
}
