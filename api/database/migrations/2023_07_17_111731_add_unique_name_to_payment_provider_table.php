<?php

use App\Models\PaymentProvider;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUniqueNameToPaymentProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $duplicateRecords = PaymentProvider::query()
            ->select('id', 'company_id', 'name')
            ->whereIn(DB::raw('(company_id, name)'), function ($query) {
                $query->select('company_id', 'name')
                    ->from('payment_provider')
                    ->groupBy('company_id', 'name')
                    ->havingRaw('COUNT(*) > 1');
            })
            ->get();

        foreach ($duplicateRecords as $duplicateRecord) {
            $newName = $duplicateRecord->name . '_' . $duplicateRecord->id;

            PaymentProvider::query()
                ->where('id', $duplicateRecord->id)
                ->update(['name' => $newName]);
        }

        Schema::table('payment_provider', function (Blueprint $table) {
            $table->dropUnique('payment_provider_id_company_id_unique');

            $table->unique(['company_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_provider', function (Blueprint $table) {
            $table->dropUnique('payment_provider_company_id_name_unique');

            $table->unique(['id', 'company_id']);
        });
    }
}
