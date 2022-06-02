<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberAccessLimitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_access_limitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('group_role_id');
            $table->unsignedBigInteger('commission_template_id');
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('group_role_id')->references('id')->on('group_role')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('commission_template_id')->references('id')->on('commission_template')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->unique(['member_id', 'group_id', 'group_role_id', 'provider_id', 'commission_template_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_access_limitations');
    }
}
