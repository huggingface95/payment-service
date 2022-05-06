<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupRoleMembersIndividualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_role_members_individuals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_role_id');
            $table->unsignedBigInteger('user_id');

            $table->unique(['group_role_id', 'user_id']);

            $table->foreign('group_role_id')->references('id')->on('group_role')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_role_members_individuals');
    }
}
