<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CommissionTemplateLimitType;

class InitCommissionTemplateLimitType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $names= [
          'All','Transaction Amount','Transaction Count','Transfer Count'
        ];
        foreach ($names as $name)
        CommissionTemplateLimitType::create([
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
