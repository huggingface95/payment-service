<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsAndForeignKeysToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('reg_address')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->timestamp('incorporate_date')->nullable();
            $table->unsignedBigInteger('employees_id')->nullable();
            $table->unsignedBigInteger('type_of_industry_id')->nullable();
            $table->string('license_number')->nullable();
            $table->timestamp('exp_date')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('state_reason_id')->nullable();

            $table->foreign('employees_id')->references('id')->on('employees');
            $table->foreign('type_of_industry_id')->references('id')->on('type_of_industries');
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('state_reason_id')->references('id')->on('state_reasons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['employees_id']);
            $table->dropForeign(['type_of_industry_id']);
            $table->dropForeign(['state_id']);
            $table->dropForeign(['state_reason_id']);

            $table->dropColumn([
                'phone',
                'reg_address',
                'tax_id',
                'incorporate_date',
                'employees_id',
                'type_of_industry_id',
                'license_number',
                'exp_date',
                'state_id',
                'state_reason_id',
            ]);
        });
    }
}
