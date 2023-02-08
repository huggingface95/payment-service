<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberAccessLimitationGroupRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_access_limitation_group_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('access_limitation_id');
            $table->unsignedBigInteger('group_role_id');

            $table->foreign('access_limitation_id')->references('id')->on('member_access_limitations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('group_role_id')->references('id')->on('group_role')->onDelete('cascade')->onUpdate('cascade');

            $table->unique(['access_limitation_id', 'group_role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_access_limitation_group_roles');
    }
}
