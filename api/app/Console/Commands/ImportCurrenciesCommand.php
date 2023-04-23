<?php

namespace App\Console\Commands;

use App\Models\CurrencyRateHistory;
use Illuminate\Console\Command;

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
        CurrencyRateHistory::factory()->create([
            'currency_src_id' => 1,
            'currency_dst_id' => 2,
        ]);

        CurrencyRateHistory::factory()->create([
            'currency_src_id' => 1,
            'currency_dst_id' => 3,
        ]);

        CurrencyRateHistory::factory()->count(10)->create();
    }
}
