<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdDepartmentPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('department_position', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unique(['name', 'company_id']);
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('department_position', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
}
