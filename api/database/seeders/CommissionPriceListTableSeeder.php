<?php

namespace Database\Seeders;

use App\Models\CommissionPriceList;
use App\Models\CommissionTemplate;
use App\Models\Companies;
use App\Models\PaymentProvider;
use App\Models\PaymentSystem;
use App\Models\Region;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class CommissionPriceListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $paymentProviders = PaymentProvider::select('id')->get();
        $paymentSystems = PaymentSystem::select('id')->get();
        $commissionTemplates = CommissionTemplate::select('id')->get();
        $companies = Companies::select('id')->get();
        $regions = Region::select('id')->get();

        CommissionPriceList::create([
            'name' => $faker->sentence(1),
            'provider_id' => $paymentProviders[0]->id,
            'payment_system_id' => $paymentSystems[0]->id,
            'commission_template_id' => 1, //$commissionTemplates[0]->id,
            'company_id' => $companies[0]->id,
            'region_id' => $regions[0]->id,
        ]);
    }
}
