<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUsersViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DROP VIEW IF EXISTS users_view');
        DB::statement("
          CREATE VIEW users_view AS
          SELECT row_number() OVER () AS id, *  FROM (
            SELECT
                m.fullname,
                m.first_name,
                m.last_name,
                m.company_id,
                m.email,
                gr.id AS group_id,
                gr.group_type_id,
                gr.role_id
               FROM ((members m
                 JOIN group_role_members_individuals grmi ON ((m.id = grmi.user_id)))
                 JOIN group_role gr ON ((gr.id = grmi.group_role_id)))
              WHERE ((grmi.user_type)::text = 'Members'::text)
            UNION
             SELECT
                ai.fullname,
                ai.first_name,
                ai.last_name,
                ai.company_id,
                ai.email,
                gr.id AS group_id,
                gr.group_type_id,
                gr.role_id
               FROM ((applicant_individual ai
                 JOIN group_role_members_individuals grmi ON ((ai.id = grmi.user_id)))
                 JOIN group_role gr ON ((gr.id = grmi.group_role_id)))
              WHERE ((grmi.user_type)::text = 'ApplicantIndividual'::TEXT)
              ) AS users_view
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_view', function (Blueprint $table) {
            DB::statement('DROP VIEW IF EXISTS users_view');
        });
    }
}
