<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeReceivedAtAndErrorFieldsInPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('error', 255)->nullable()->change();
            $table->string('payment_number', 255)->nullable()->change();

            $table->dropForeign(['owner_id']);
            $table->dropColumn('owner_id');
        });

        DB::statement('ALTER TABLE payments ALTER "received_at" DROP DEFAULT, ALTER "received_at" DROP NOT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('error', 255)->nullable(false)->change();
            $table->string('payment_number', 255)->nullable(false)->change();

            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')->references('id')->on('applicant_individual')->onUpdate('cascade');
        });

        DB::statement('ALTER TABLE "payments" ALTER "received_at" DROP DEFAULT, ALTER "received_at" SET NOT NULL;');
    }
}
