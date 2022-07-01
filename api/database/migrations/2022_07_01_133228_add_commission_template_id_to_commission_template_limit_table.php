<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommissionTemplateIdToCommissionTemplateLimitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_template_limit', function (Blueprint $table) {
            $table->unsignedBigInteger('commission_template_id');

            $table->foreign('commission_template_id')->references('id')->on('commission_template')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commission_template_limit', function (Blueprint $table) {
            $table->dropColumn('commission_template_id');
        });
    }
}
