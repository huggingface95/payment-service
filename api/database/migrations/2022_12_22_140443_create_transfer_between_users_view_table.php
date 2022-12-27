<?php

use App\Enums\OperationTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTransferBetweenUsersViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW transfer_between_users_view AS
                SELECT
                    payment_status.name as status_name,
                    payment_bank_id,
                    payment_system_id,
                    company_id,
                    payment_provider_id,
                    operation_type_id,
                    status_id,
                    created_at::date
                FROM transfer_outgoings
                JOIN payment_status ON (transfer_outgoings.status_id=payment_status.id)
                WHERE transfer_outgoings.operation_type_id=" . OperationTypeEnum::BETWEEN_USERS->value . "
                    UNION ALL
                SELECT
                    payment_status.name as status_name,
                    payment_bank_id,
                    payment_system_id,
                    company_id,
                    payment_provider_id,
                    operation_type_id,
                    status_id,
                    created_at::date
                FROM transfer_incomings
                JOIN payment_status ON (transfer_incomings.status_id=payment_status.id)
                WHERE transfer_incomings.operation_type_id=" . OperationTypeEnum::BETWEEN_USERS->value . "
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS transfer_between_users_view');
    }
}
