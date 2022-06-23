<?php

use App\Models\EmailNotification;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('group_id');
            $table->enum('type', [EmailNotification::ADMINISTRATION, EmailNotification::CLIENT])->default(EmailNotification::ADMINISTRATION);
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')
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
        Schema::dropIfExists('email_notifications');
    }
}
