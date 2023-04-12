<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueOperationTypeIdPriceListFeeIdFeeTypeModeIdToFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->dropUnique('fees_operation_type_id_price_list_fee_id_unique');
            $table->unique(['operation_type_id', 'price_list_fee_id', 'fee_type_mode_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->dropUnique('fees_operation_type_id_price_list_fee_id_fee_type_mode_id_unique');
        });
    }
}
