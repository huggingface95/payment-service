<?php

use App\Models\EmailNotification;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateToEmailNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_notifications', function (Blueprint $table) {
            $table->dropColumn(['member_id', 'group_id']);
            $table->enum(
                'recipient_type',
                [EmailNotification::RECIPIENT_GROUP, EmailNotification::RECIPIENT_PERSON]
            )
                ->default(EmailNotification::RECIPIENT_GROUP);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_notifications', function (Blueprint $table) {
            $table->dropColumn('recipient_type');
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('group_id');
        });
    }
}
