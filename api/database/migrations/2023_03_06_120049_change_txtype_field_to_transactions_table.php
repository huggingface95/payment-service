<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeTxtypeFieldToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $this->changeEnum('transactions', 'txtype', ['income', 'outgoing', 'fee', 'internal', 'exchange']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $this->changeEnum('transactions', 'txtype', ['income', 'outgoing', 'fee', 'internal']);
        });
    }

    private function changeEnum(string $table, string $column, array $types): void
    {
        DB::statement("ALTER TABLE $table DROP CONSTRAINT ".$table.'_'.$column.'_check');

        $result = implode(', ', array_map(function ($value) {
            return sprintf("'%s'::character varying", $value);
        }, $types));

        DB::statement("ALTER TABLE $table ADD CONSTRAINT ".$table.'_'.$column.'_check CHECK ('.$column."::text = ANY (ARRAY[$result]::text[]))");
        DB::statement("ALTER TABLE $table ALTER COLUMN ".$column." SET DEFAULT '".$types[0]."'");
    }
}
