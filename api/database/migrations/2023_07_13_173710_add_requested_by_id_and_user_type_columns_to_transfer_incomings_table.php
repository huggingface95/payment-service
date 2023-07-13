<?php

use App\Enums\GuardEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequestedByIdAndUserTypeColumnsToTransferIncomingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->unsignedBigInteger('requested_by_id')->nullable();
            $table->enum('user_type', [GuardEnum::GUARD_INDIVIDUAL->toString(), GuardEnum::GUARD_MEMBER->toString()])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_incomings', function (Blueprint $table) {
            $table->dropColumn(['requested_by_id', 'user_type']);
        });
    }
}
