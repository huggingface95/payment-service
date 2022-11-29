<?php

use App\Enums\MemberStatusEnum;
use App\Models\Members;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\MemberStatusTableSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIsActiveFieldToMemberStatusIdFieldInMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        (new DatabaseSeeder())->call(MemberStatusTableSeeder::class);

        Schema::table('members', function (Blueprint $table) {
            $table->unsignedInteger('member_status_id')->default(MemberStatusEnum::INACTIVE->value);
            $table->foreign('member_status_id')->references('id')->on('member_statuses');
        });

        Members::where('is_active', 1)->update(['member_status_id' => 1]);

        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['member_status_id']);
            $table->boolean('is_active')->default('true');
        });

        Members::where('member_status_id', 2)->update(['is_active' => 1]);

        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('member_status_id');
        });
    }
}
