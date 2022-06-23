<?php

use App\Models\CommissionTemplateLimitPeriod;
use Illuminate\Database\Migrations\Migration;

class InitCommissionTemplateLimitPeriod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $names = [
            'Each time', 'One time', 'Daily', 'Weekly', 'Monthly', 'Yearly',
        ];
        foreach ($names as $name) {
            CommissionTemplateLimitPeriod::create([
                'name' => $name,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
