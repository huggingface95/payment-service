<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailNotificationTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_notification_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_notification_id');
            $table->unsignedBigInteger('email_template_id');

            $table->foreign('email_notification_id')->references('id')->on('email_notifications')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('email_template_id')->references('id')->on('email_templates')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_notification_templates');
    }
}
