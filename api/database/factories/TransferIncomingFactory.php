<?php

namespace Database\Factories;

use App\Enums\TransferChannelEnum;
use App\Enums\TransferTypeEnum;
use App\Models\TransferIncoming;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransferIncomingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransferIncoming::class;

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
            'amount_debt' => $amount * 0.9,
            'currency_id' => 1,
            'status_id' => $this->faker->numberBetween(1, 7),
            'urgency_id' => $this->faker->numberBetween(1, 2),
            'operation_type_id' => TransferTypeEnum::INCOMING_WIRE_TRANSFER->value,
            'payment_provider_id' => 1,
            'payment_system_id' => 1,
            'payment_bank_id' => 1,
            'payment_number' => $this->faker->randomNumber(),
            'account_id' => 1,
            'recipient_id' => 1,
            'recipient_type' => $this->faker->randomElement([class_basename(ApplicantIndividual::class), class_basename(ApplicantCompany::class)]),
            'company_id' => 1,
            'system_message' => 'System msg: '.$this->faker->name(),
            'reason' => $this->faker->name(),
            'channel' => $this->faker->randomElement([
                TransferChannelEnum::CLIENT_DASHBOARD->toString(),
                TransferChannelEnum::BACK_OFFICE->toString(),
                TransferChannelEnum::CLIENT_MOBILE_APPLICATION->toString(),
            ]),
            'bank_message' => 'Bank msg: '.$this->faker->name(),
            'sender_account' => $this->faker->name(),
            'sender_bank_name' => 'Bank'.$this->faker->company,
            'sender_bank_address' => $this->faker->address(),
            'sender_bank_swift' => $this->faker->swiftBicNumber(),
            'sender_bank_country_id' => $this->faker->numberBetween(1, 10),
            'sender_name' => $this->faker->name(),
            'sender_country_id' => $this->faker->numberBetween(1, 10),
            'sender_city' => $this->faker->city(),
            'sender_address' => $this->faker->address(),
            'sender_state' => $this->faker->state,
            'sender_zip' => $this->faker->numberBetween(100000, 300000),
            'respondent_fees_id' => $this->faker->randomElement([1, 2, 3]),
            'execution_at' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
            'created_at' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
            'updated_at' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
            'group_id' => 1,
            'group_type_id' => 1,
            'project_id' => 1,
            'price_list_id' => 1,
            'price_list_fee_id' => 1,
            'beneficiary_name' => 'Beneficiary '.$this->faker->name(),
            'beneficiary_type_id' => $this->faker->randomElement([1, 2]),
        ];
    }
}
