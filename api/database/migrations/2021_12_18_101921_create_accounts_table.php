<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('owner_id');
            $table->string('account_number', 255)->unique();
            $table->enum('account_type', ['Business', 'Private']);
            $table->unsignedBigInteger('payment_provider_id');
            $table->unsignedBigInteger('commission_template_id');
            $table->unsignedBigInteger('account_state_id');
            $table->string('account_name', 255)->unique();
            $table->boolean('is_primary');
            $table->decimal('current_balance', 15, 5)->default(0);
            $table->decimal('reserved_balance', 15, 5)->default(0);
            $table->decimal('available_balance', 15, 5)->default(0);
            $table->timestamps();
            $table->timestamp('activated_at')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('client_id')->references('id')->on('applicant_individual');
            $table->foreign('owner_id')->references('id')->on('members');
            $table->foreign('payment_provider_id')->references('id')->on('payment_provider');
            $table->foreign('commission_template_id')->references('id')->on('commission_template');
            $table->foreign('account_state_id')->references('id')->on('account_states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
