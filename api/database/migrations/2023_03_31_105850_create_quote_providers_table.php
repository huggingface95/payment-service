<?php

use App\Enums\ActivityStatusEnum;
use App\Enums\QuoteTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('company_id');
            $table->enum('status', [ActivityStatusEnum::INACTIVE->value, ActivityStatusEnum::ACTIVE->value]);
            $table->enum('quote_type', [QuoteTypeEnum::API->toString(), QuoteTypeEnum::MANUAL->toString()]);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quote_providers');
    }
}
