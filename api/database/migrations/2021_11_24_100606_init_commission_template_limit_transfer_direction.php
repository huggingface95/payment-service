<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CommissionTemplateLimitTransferDirection;

class InitCommissionTemplateLimitTransferDirection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $names= [
            'All','Incoming','Outgoing'
        ];
        foreach ($names as $name)
            CommissionTemplateLimitTransferDirection::create([
                'name' => $name,
            ]);
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
