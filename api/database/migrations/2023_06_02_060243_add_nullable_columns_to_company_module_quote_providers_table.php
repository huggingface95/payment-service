<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableColumnsToCompanyModuleQuoteProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_module_quote_providers', function (Blueprint $table) {
            $table->string('wallet')->nullable()->change();
            $table->string('api_key')->nullable()->change();
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_module_quote_providers', function (Blueprint $table) {
            $table->string('wallet')->change();
            $table->string('api_key')->change();
            $table->string('password')->change();
        });
    }
}
