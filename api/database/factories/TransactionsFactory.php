<?php

namespace Database\Factories;

use App\Models\Transactions;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transactions::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $amount = $this->faker->randomNumber(3);

        return [
            'company_id' => 1,
            'currency_src_id' => 1,
            'currency_dst_id' => 1,
            'balance_prev' => $amount,
            'balance_next' => $amount * 0.5,
            'amount' => $amount * 0.5,
            'txtype' => $this->faker->randomElement(['income', 'outgoing', 'fee', 'internal']),
            'transfer_id' => 1,
            'transfer_type' => $this->faker->randomElement([class_basename(TransferOutgoing::class), class_basename(TransferIncoming::class)]),
            'created_at' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
            'updated_at' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
        ];
    }
}
