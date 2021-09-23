<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberDepartmentPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_department_position', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id');
            $table->integer('department_position_id');
            $table->foreign('department_position_id')->references('id')->on('department_position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_department_position');
    }
}
