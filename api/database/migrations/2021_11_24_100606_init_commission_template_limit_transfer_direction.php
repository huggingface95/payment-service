<?php

use App\Models\CommissionTemplateLimitTransferDirection;
use Illuminate\Database\Migrations\Migration;

class InitCommissionTemplateLimitTransferDirection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $names = [
            'All', 'Incoming', 'Outgoing',
        ];
        foreach ($names as $name) {
            CommissionTemplateLimitTransferDirection::create([
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
