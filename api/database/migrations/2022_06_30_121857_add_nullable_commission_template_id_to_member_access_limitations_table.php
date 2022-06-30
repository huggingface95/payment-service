<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableCommissionTemplateIdToMemberAccessLimitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_access_limitations', function (Blueprint $table) {
            $table->unsignedBigInteger('commission_template_id')->nullable()->change();
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
            $table->unsignedBigInteger('commission_template_id')->change();
        });
    }
}
