<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMethodAndTypeColumnsToPermissionOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permission_operations', function (Blueprint $table) {
            $table->string('method')->nullable();
            $table->enum('type', ['query', 'mutation', 'subscription'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permission_operations', function (Blueprint $table) {
            $table->dropColumn(['method', 'type']);
        });
    }
}
