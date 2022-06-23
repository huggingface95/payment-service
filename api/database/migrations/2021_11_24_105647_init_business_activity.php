<?php

use App\Models\BusinessActivity;
use Illuminate\Database\Migrations\Migration;

class InitBusinessActivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $names = [
            'E-commerce', 'Retail',
        ];
        foreach ($names as $name) {
            BusinessActivity::create([
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
