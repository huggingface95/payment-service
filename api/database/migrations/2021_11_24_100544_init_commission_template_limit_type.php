<?php

use App\Models\CommissionTemplateLimitType;
use Illuminate\Database\Migrations\Migration;

class InitCommissionTemplateLimitType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $names = [
            'All', 'Transaction Amount', 'Transaction Count', 'Transfer Count',
        ];
        foreach ($names as $name) {
            CommissionTemplateLimitType::create([
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
