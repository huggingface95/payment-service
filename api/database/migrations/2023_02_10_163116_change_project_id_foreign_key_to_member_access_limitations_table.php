<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeProjectIdForeignKeyToMemberAccessLimitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_access_limitations', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_access_limitations', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }
}
