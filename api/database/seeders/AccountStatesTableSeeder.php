<?php

namespace Database\Seeders;

use App\Models\AccountState;
use Illuminate\Database\Seeder;

class AccountStatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accountStates = ['Waiting for approval','Waiting for Account# Generation','Awaiting Account#','Active','Closed','Suspended','Rejected'];
        foreach ($accountStates as $accountState)
        {
            AccountState::firstOrCreate(['name'=>$accountState]);
        }
    }
}
