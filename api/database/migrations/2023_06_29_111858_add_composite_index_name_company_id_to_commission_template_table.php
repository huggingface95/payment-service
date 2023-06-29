<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompositeIndexNameCompanyIdToCommissionTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_template', function (Blueprint $table) {
            $table->dropUnique('commission_template_name_unique');
            $table->unique(['company_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commission_template', function (Blueprint $table) {
            $table->dropUnique('commission_template_company_id_name_unique');
            $table->unique('name');
        });
    }
}
