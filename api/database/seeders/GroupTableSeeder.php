<?php

namespace Database\Seeders;

use App\Models\GroupType;
use Illuminate\Database\Seeder;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groupTypes = [
            1 => 'Member',
            2 => 'Company',
            3 => 'Individual',
        ];

        foreach ($groupTypes as $id => $type) {
            GroupType::firstOrCreate([
                'id' => $id,
                'name' => $type,
            ]);
        }
    }
}
