<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupRoleMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_role_member', function (Blueprint $table) {
            $table->unsignedBigInteger('group_role_id');
            $table->unsignedBigInteger('member_id');
            $table->unique(['group_role_id','member_id']);
            $table->foreign('group_role_id')->references('id')->on('group_role');
            $table->foreign('member_id')->references('id')->on('members');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_role_member');
    }
}
