<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompositeUniqueIndexToCommissionTemplateLimitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_template_limit', function (Blueprint $table) {
            $table->unique(['commission_template_id',
                    'commission_template_limit_type_id',
                    'commission_template_limit_transfer_direction_id',
                    'commission_template_limit_period_id',
                    'commission_template_limit_action_type_id'
            ], 'composite_unique_commission_template_limit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commission_template_limit', function (Blueprint $table) {
            $table->dropIndex('composite_unique_commission_template_limit');
        });
    }
}
