<?php

use App\Models\PriceListPPFee;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUniqueKeyToPriceListPpFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $duplicates = PriceListPPFee::select('name', 'company_id', DB::raw('count(*)'))
            ->groupBy('name', 'company_id')
            ->havingRaw('count(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            for ($i = 1; $i <= $duplicate->count; $i++) {
                $priceListPPFee = PriceListPPFee::where('name', $duplicate->name)
                    ->where('company_id', $duplicate->company_id)
                    ->first();
                $priceListPPFee->name = $duplicate->name . '_' . $i;
                $priceListPPFee->save();
            }
        }
          
        Schema::table('price_list_pp_fees', function (Blueprint $table) {
            $table->unique(['name', 'company_id'], 'price_list_pp_fees_name_company_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_list_pp_fees', function (Blueprint $table) {
            $table->dropUnique('price_list_pp_fees_name_company_id_unique');
        });
    }
}
