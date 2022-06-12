<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSendingMailSmtp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_smtps', function (Blueprint $table) {
            $table->boolean('is_sending_mail')->default(false);
            $table->string('name',255)->nullable();
            $table->dropColumn('email_setting_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_smtps', function (Blueprint $table) {
            $table->dropColumn('is_sending_mail');
            $table->dropColumn('name');
            $table->unsignedBigInteger('email_setting_id');
        });
    }
}
