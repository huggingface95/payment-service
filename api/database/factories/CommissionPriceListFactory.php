<?php

namespace Database\Factories;

use App\Models\CommissionPriceList;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommissionPriceListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CommissionPriceList::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'provider_id' => 1,
            'payment_system_id' => 2,
            'commission_template_id' => 3,
        ];
    }
}
