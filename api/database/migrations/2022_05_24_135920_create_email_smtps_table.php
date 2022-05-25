<?php

use App\Models\EmailSmtp;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailSmtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_smtps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->enum('security', EmailSmtp::getSecurities())->nullable();
            $table->string('name');
            $table->string('host_name');
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->string('username');
            $table->string('password');
            $table->string('replay_to')->nullable();
            $table->unsignedSmallInteger('port');
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_smtps');
    }
}
