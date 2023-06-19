<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeDeletedAtInGroupRoleViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DROP VIEW IF EXISTS group_role_view');

        DB::statement('
            CREATE VIEW group_role_view AS
                SELECT
                    group_role.*,
                    payment_provider.id as payment_provider_id,
                    payment_provider.name as payment_provider_name,
                    commission_template.name as commission_template_name,
                    commission_template.id as commission_template_id
                FROM
                    group_role
                LEFT JOIN group_role_providers ON
                    group_role.id = group_role_providers.group_role_id
                LEFT JOIN payment_provider ON
                    group_role_providers.payment_provider_id = payment_provider.id
                LEFT JOIN commission_template ON
                    group_role_providers.commission_template_id = commission_template.id
                WHERE group_role.deleted_at IS NULL

        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS group_role_view');

        DB::statement('
            CREATE VIEW group_role_view AS
                SELECT
                    group_role.*,
                    payment_provider.id as provider_id,
                    payment_provider.name as provider_name,
                    commission_template.name as commission_template_name,
                    commission_template.id as commission_template_id
                FROM
                    group_role
                LEFT JOIN group_role_providers ON
                    group_role.id = group_role_providers.group_role_id
                LEFT JOIN payment_provider ON
                    group_role_providers.payment_provider_id = payment_provider.id
                LEFT JOIN commission_template ON
                    group_role_providers.commission_template_id = commission_template.id
        ');
    }
}
