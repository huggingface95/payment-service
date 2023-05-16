<?php

namespace Database\Seeders;

use App\GraphQL\Mutations\TransferExchangeMutator;
use Faker\Factory;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferExchangeTableSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seqIncoming = DB::table('transfer_incomings')
                ->max('id') + 1;
        DB::select('ALTER SEQUENCE transfer_incomings_id_seq RESTART WITH '.$seqIncoming);

        $seqOutgoings = DB::table('transfer_outgoings')
                ->max('id') + 1;
        DB::select('ALTER SEQUENCE transfer_outgoings_id_seq RESTART WITH '.$seqOutgoings);

        $container = Container::getInstance();
        $mutation = $container->make(TransferExchangeMutator::class);

        $amount = $this->faker->randomNumber(3);

        $accountIds = [3, 4];
        $uniqueAccountPairs = $this->faker->randomElements($accountIds, 2, false);

        $fromAccountId = $uniqueAccountPairs[0];
        $toAccountId = $uniqueAccountPairs[1];

        for ($i = 1; $i <= 10; $i++) {
            $mutation->create(
                null,
                [
                    'amount' => $amount,
                    'from_account_id' => $fromAccountId,
                    'to_account_id' => $toAccountId,
                    'price_list_fee_id' => $this->faker->randomElement([1, 2]),
                ]
            );

            $uniqueAccountPairs = $this->faker->randomElements($accountIds, 2, false);
            $fromAccountId = $uniqueAccountPairs[0];
            $toAccountId = $uniqueAccountPairs[1];
        }
    }
}
