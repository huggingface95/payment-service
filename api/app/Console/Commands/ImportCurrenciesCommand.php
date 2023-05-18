<?php

namespace App\Console\Commands;

use App\Models\CurrencyExchangeRate;
use App\Models\CurrencyRateHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCurrenciesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:import-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import currencies from external API';

    /**
     * Create a new command instance.
     */
    public function __construct(
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currencies = [
            [
                'currency_src_id' => 1,
                'currency_dst_id' => 1,
            ],
            [
                'currency_src_id' => 1,
                'currency_dst_id' => 2,
            ],
            [
                'currency_src_id' => 1,
                'currency_dst_id' => 3,
            ],
            [
                'currency_src_id' => 1,
                'currency_dst_id' => 4,
            ],
            [
                'currency_src_id' => 1,
                'currency_dst_id' => 5,
            ],
        ];

        foreach ($currencies as $currency) {
            $this->logAndUpdateCurrency($currency);
        }
    }

    private function logAndUpdateCurrency($arr): void
    {
        try {
            DB::beginTransaction();

            $currency = CurrencyRateHistory::factory()->create($arr);

            CurrencyExchangeRate::updateOrCreate([
                'currency_from_id' => $currency->currency_src_id,
                'currency_to_id' => $currency->currency_dst_id,
                'quote_provider_id' => $currency->quote_provider_id,
            ], [
                'rate' => $currency->rate,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e->getMessage();
        }
    }
}
