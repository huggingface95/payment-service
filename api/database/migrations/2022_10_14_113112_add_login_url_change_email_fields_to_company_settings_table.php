<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoginUrlChangeEmailFieldsToCompanySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->renameColumn('email_url', 'client_url');
            $table->renameColumn('email_from', 'support_email');
            $table->string('login_url', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->renameColumn('client_url', 'email_url');
            $table->renameColumn('support_email', 'email_from');
            $table->dropColumn('login_url');
        });
    }
}
