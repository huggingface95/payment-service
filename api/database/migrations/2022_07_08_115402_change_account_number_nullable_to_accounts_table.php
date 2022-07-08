<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeAccountNumberNullableToAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropUnique('accounts_account_number_unique');
            $table->dropUnique('accounts_account_name_unique');
        });

        DB::statement("CREATE UNIQUE INDEX accounts_name_number_unique ON accounts (account_name, (account_number IS NULL)) WHERE account_number IS NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->unique('account_number');
            $table->unique('account_name');
            $table->dropUnique('accounts_name_number_unique');
        });
    }
}
