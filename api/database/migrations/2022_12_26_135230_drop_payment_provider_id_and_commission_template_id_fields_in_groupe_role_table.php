<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropPaymentProviderIdAndCommissionTemplateIdFieldsInGroupeRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_role', function (Blueprint $table) {
            $table->dropForeign(['payment_provider_id']);
            $table->dropForeign(['commission_template_id']);

            $table->dropColumn(['payment_provider_id', 'commission_template_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_role', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_provider_id')->nullable();
            $table->unsignedBigInteger('commission_template_id')->nullable();

            $table->foreign('payment_provider_id')->references('id')->on('payment_provider');
            $table->foreign('commission_template_id')->references('id')->on('commission_template');
        });
    }
}
