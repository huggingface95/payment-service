<?php

use App\Models\PriceListPPFee;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdFieldToPriceListPpFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        PriceListPPFee::truncate();
        Schema::table('price_list_pp_fees', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('name')->nullable()->change();
            $table->unsignedInteger('type_id')->nullable()->change();
            $table->unsignedInteger('period_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_list_pp_fees', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');

            $table->string('name')->nullable(false)->change();
            $table->unsignedInteger('type_id')->nullable(false)->change();
            $table->unsignedInteger('period_id')->nullable(false)->change();
        });
    }
}
