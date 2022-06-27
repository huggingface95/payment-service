<?php

use App\Models\ApplicantCompanyBusinessType;
use Illuminate\Database\Migrations\Migration;

class InitApplicantCompanyBusinessTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $businessTypes = ['Financial Institution',
            'Electronics Stories',
            'Retail',
            'Media',
            'Affilates',
            'Online advertising',
            'Marketing',
            'Consultancy',
            'Independent legal professionals',
            'Tax advisors',
            'Social Service and Charity',
            'Gambling',
            'Digital Goods',
            'Electronic Goods',
            'Health and beauty',
            'Vouchers',
            'Shop',
            'Computer Software',
            'Advertising Services',
            'Food Stores',
            'Saas',
            'Telecommunication Services',
            'Travel Agency',
            'Computer Network services',
            'Social Networks',
            'Hosting',
            'E-commerce',
            'IT development',
            'Intermediary services',
            'Forex',
            'Activities involving gambling',
            'gaming and/or any other activity with an entry fee and a prize',
            'including but not limited to casino games',
            'lottery',
            'games of skill and sweepstakes (only regulated)',
            'Crypto-related',
            'PSP',
            'Financial services',
            'Money transfer services',
            'Insurance',
            'Logistics',
            'Provision of legal services',
            'Legal practice or notary practice',
            'Real estate',
            'Well known dating sites',
            'Adult services (sale of goods, websites, etc.)', ];

        foreach ($businessTypes as $type) {
            ApplicantCompanyBusinessType::create(['name'=> $type]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
