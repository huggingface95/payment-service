<?php

namespace Database\Seeders;

use App\Models\PriceListQpFee;
use App\Models\QuoteProvider;
use Illuminate\Database\Seeder;

class PriceListQpFeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quoteProviders = QuoteProvider::limit(10)->get();

        foreach ($quoteProviders as $provider) {
            PriceListQpFee::updateOrCreate([
                'name' => 'Test QP fee '.$provider->id,
                'quote_provider_id' => $provider->id,
                'period_id' => 1,
                'type_id' => 1,
            ]);
        }
    }
}
