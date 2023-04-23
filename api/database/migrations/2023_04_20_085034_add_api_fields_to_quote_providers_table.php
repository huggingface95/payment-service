<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApiFieldsToQuoteProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote_providers', function (Blueprint $table) {
            $table->string('api_url')->nullable();
            $table->string('api_secret')->nullable();
            $table->decimal('margin_commission', 5, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_providers', function (Blueprint $table) {
            $table->dropColumn('api_url');
            $table->dropColumn('api_secret');
            $table->dropColumn('margin_commission');
        });
    }
}
