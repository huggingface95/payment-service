<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegionCommissionTemplateLimit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_template_limit', function (Blueprint $table) {
            $table->unsignedBigInteger('region_id')->nullable();

            $table->foreign('region_id')->references('id')->on('regions')
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
            $table->dropColumn('region_id');
        });
    }
}
