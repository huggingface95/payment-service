<?php

use App\Enums\PaymentUrgencyEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class ChangePaymentUrgencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_urgency', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('payment_urgency', function (Blueprint $table) {
            $table->string('name')->nullable();
        });

        Artisan::call('db:seed', [
            '--class' => 'PaymentUrgencyTableSeeder',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_urgency', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('payment_urgency', function (Blueprint $table) {
            $table->enum('name', [PaymentUrgencyEnum::STANDART->toString(), PaymentUrgencyEnum::EXPRESS->toString()])
                ->default(PaymentUrgencyEnum::STANDART->toString());
        });

        Artisan::call('db:seed', [
            '--class' => 'PaymentUrgencyTableSeeder',
        ]);
    }
}
