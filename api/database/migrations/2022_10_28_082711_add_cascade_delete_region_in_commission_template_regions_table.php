<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeDeleteRegionInCommissionTemplateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_template_regions', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->foreign('region_id')
                ->references('id')
                ->on('regions')
                ->onDelete('cascade')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commission_template_regions', function (Blueprint $table) {
            //
        });
    }
}
