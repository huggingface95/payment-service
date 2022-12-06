<?php

namespace Database\Factories;

use App\Enums\FeeTransferTypeEnum;
use App\Models\Fee;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Fee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fee = $this->faker->randomNumber(3);

        return [
            'fee' => $fee,
            'fee_pp' => $fee * 0.9,
            'fee_type_id' => 1,
            'operation_type_id' => 1,
            'member_id' => null,
            'status_id' => 1,
            'client_id' => 1,
            'client_type' => $this->faker->randomElement([class_basename(ApplicantIndividual::class), class_basename(ApplicantCompany::class)]),
            'account_id' => 1,
            'price_list_fee_id' => 1,
            'transfer_type' => FeeTransferTypeEnum::OUTGOING->toString(),
        ];
    }
}
