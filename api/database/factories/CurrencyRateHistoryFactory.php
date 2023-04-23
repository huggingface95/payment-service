<?php

namespace Database\Factories;

use App\Models\Currencies;
use App\Models\CurrencyRateHistory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CurrencyRateHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CurrencyRateHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $maxCurrencyId = Currencies::query()->max('id');

        return [
            'quote_provider_id' => 1,
            'currency_src_id' => $this->faker->numberBetween(1, $maxCurrencyId),
            'currency_dst_id' => $this->faker->numberBetween(1, $maxCurrencyId),
            'rate' => $this->faker->randomFloat(5, 0.5, 2.5),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
    }
}
