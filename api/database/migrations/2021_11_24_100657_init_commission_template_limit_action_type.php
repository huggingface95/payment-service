<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CommissionTemplateLimitActionType;

class InitCommissionTemplateLimitActionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $names= [
            'Notify','Suspend'
        ];
        foreach ($names as $name)
            CommissionTemplateLimitActionType::create([
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
