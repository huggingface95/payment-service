<?php

namespace App\Console\Commands;

use App\Jobs\IbanCompanyActivationJob;
use App\Models\Accounts;
use App\Models\AccountState;
use Illuminate\Console\Command;

class IbanCompanyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iban:company:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check iban company successfully';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companyIbanActivateAccounts = Accounts::query()
            ->where('status', AccountState::WAITING_IBAN_ACTIVATION)
            ->whereHas('applicantCompany')
            ->get();

        /** @var Accounts $account */
        foreach ($companyIbanActivateAccounts as $account) {
            dispatch(new IbanCompanyActivationJob($account));
        }
    }
}
