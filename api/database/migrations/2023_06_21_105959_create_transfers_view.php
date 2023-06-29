<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTransfersView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW transfers_view AS
            select *
from ((select tt.*,COALESCE(SUM(f.fee), 0)::NUMERIC(15,5) fee_amount
       FROM (select t.id               transfer_id,
                    'TransferIncoming' transfer_type,
                    t.created_at,
                    t.amount,
                    t.amount_debt,
                    t.company_id,
                    t.operation_type_id,
                    ot.transfer_type_id,
                    t.status_id        payment_status_id,
                    t.reason,
                    a.account_name     to_account,
                    t.sender_account   from_account,
                    a.client_id        client_to_id,
                    a.client_type      client_to_type,
                    t.recipient_id     client_from_id,
                    t.recipient_type   client_from_type
             FROM transfer_incomings t
                      left join accounts a on t.account_id = a.id
                      left join operation_type ot on t.operation_type_id = ot.id
             ) tt
                left join fees f on tt.transfer_id = f.transfer_id and
                                    f.transfer_type = 'Incoming'

       group by tt.transfer_id,tt.transfer_type,tt.created_at,tt.amount,tt.amount_debt,tt.company_id,tt.operation_type_id,tt.transfer_type_id,tt.payment_status_id,
                tt.reason,tt.to_account,tt.from_account,tt.client_to_id,tt.client_to_type,tt.client_from_id,tt.client_from_type
       )
      UNION
      (select tt.*,COALESCE(SUM(f.fee), 0)::NUMERIC(15,5) fee_amount
       FROM (select t.id                transfer_id,
                    'TransferOutgoing'  transfer_type,
                    t.created_at,
                    t.amount,
                    t.amount_debt,
                    t.company_id,
                    t.operation_type_id,
                    ot.transfer_type_id,
                    t.status_id         payment_status_id,
                    t.reason,
                    t.recipient_account to_account,
                    a.account_name      from_account,
                    t.sender_id         client_to_id,
                    t.sender_type       client_to_type,
                    a.client_id         client_from_id,
                    a.client_type       client_from_type
             FROM transfer_outgoings t
                      left join accounts a on t.account_id = a.id
                      left join operation_type ot on t.operation_type_id = ot.id) tt
                left join fees f on tt.transfer_id = f.transfer_id and
                                    f.transfer_type = 'Outgoing'
       group by tt.transfer_id,tt.transfer_type,tt.created_at,tt.amount,tt.amount_debt,tt.company_id,tt.operation_type_id,tt.transfer_type_id,tt.payment_status_id,
                tt.reason,tt.to_account,tt.from_account,tt.client_to_id,tt.client_to_type,tt.client_from_id,tt.client_from_type
       )) AS transfers
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers_view');
    }
}
