<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUniqueInPriceListFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_list_fees', function (Blueprint $table) {
            $table->dropUnique('price_list_fees_name_price_list_uniq');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_list_fees', function (Blueprint $table) {
            $table->unique(['name', 'price_list_id'], 'price_list_fees_name_price_list_uniq');
        });
    }
}
