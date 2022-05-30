<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemplateIdToEmailSmtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_smtps', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->unsignedBigInteger('email_template_id');
            $table->foreign('email_template_id')->references('id')->on('email_templates')->onDelete('cascade')->onUpdate('cascade');
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
            $table->string('name')->nullable();
            $table->dropColumn('email_template_id');
        });
    }
}
