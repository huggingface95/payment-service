<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnEmailSmtp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_smtps', function (Blueprint $table) {
            $table->dropForeign(['email_template_id']);
            $table->dropColumn('email_template_id');
            $table->unsignedBigInteger('email_setting_id');
            $table->foreign('email_setting_id')->references('id')->on('email_settings')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
