<?php

namespace Database\Factories;

use App\Models\Payments;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $amount = $this->faker->randomNumber(3);

        return [
            'amount' => $amount,
            'amount_real' => $amount * 0.9,
            'fee' => $this->faker->randomDigit(),
            'fee_type_id' => 1,
            'currency_id' => 1,
            'status_id' => 1,
            'urgency_id' => $this->faker->numberBetween(1, 2),
            'payment_provider_id' => 1,
            'account_id' => 1,
            'company_id' => 1,
            'member_id' => 2,
            'payment_number' => $this->faker->randomNumber(),
            'error' => 'Error' . $this->faker->text(50),
            'received_at' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
            'created_at' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
            'updated_at' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
            'operation_type_id' => 1,
            'price_list_fees_id' => 1,
            'recipient_account' => $this->faker->name(),
            'recipient_bank_name' => 'Bank' . $this->faker->company,
            'recipient_bank_address' => $this->faker->address(),
            'recipient_bank_swift' => $this->faker->swiftBicNumber(),
            'recipient_bank_country_id' => $this->faker->numberBetween(1, 10),
            'beneficiary_name' => $this->faker->name(),
            'beneficiary_state' => $this->faker->state,
            'beneficiary_country_id' => $this->faker->numberBetween(1, 10),
            'beneficiary_address' => $this->faker->address(),
            'beneficiary_city' => $this->faker->city(),
            'beneficiary_zip' => $this->faker->numberBetween(100000, 300000),
            'beneficiary_additional_data' => json_encode(['data' => $this->faker->text(30)]),
            'respondent_fees_id' => 1,
            'execution_at' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
        ];
    }
}
