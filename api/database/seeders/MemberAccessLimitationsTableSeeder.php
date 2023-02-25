<?php

namespace Database\Seeders;

use App\Models\MemberAccessLimitation;
use Illuminate\Database\Seeder;

class MemberAccessLimitationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            MemberAccessLimitation::firstOrCreate([
                'member_id' => 4,
                'company_id' => 2,
                'module_id' => 1,
                'project_id' => 1,
                'payment_provider_id' => 1,
                'group_type_id' => 1,
                'see_own_applicants' => true,
            ]);
    }
}
