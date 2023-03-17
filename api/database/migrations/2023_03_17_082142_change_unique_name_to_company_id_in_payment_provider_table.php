<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUniqueNameToCompanyIdInPaymentProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_provider', function (Blueprint $table) {
            $table->dropUnique('payment_provider_name_unique');

            $table->unique(['id', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_provider', function (Blueprint $table) {
            $table->dropUnique('payment_provider_id_company_id_unique');

            $table->unique('name');
        });
    }
}
