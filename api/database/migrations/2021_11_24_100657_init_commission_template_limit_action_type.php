<?php

use App\Models\CommissionTemplateLimitActionType;
use Illuminate\Database\Migrations\Migration;

class InitCommissionTemplateLimitActionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $names = [
            'Notify', 'Suspend',
        ];
        foreach ($names as $name) {
            CommissionTemplateLimitActionType::create([
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
