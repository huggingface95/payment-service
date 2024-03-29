<?php

namespace App\Console\Commands;

use App\Jobs\IbanCompanyActivationJob;
use App\Models\Account;
use App\Models\AccountState;
use App\Models\ApplicantCompany;
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
        $companyIbanActivateAccounts = Account::query()
            ->where('account_state_id', AccountState::WAITING_FOR_ACCOUNT_GENERATION)
            ->whereHasMorph('clientable', [ApplicantCompany::class])
            ->get();

        /** @var Account $account */
        foreach ($companyIbanActivateAccounts as $account) {
            dispatch(new IbanCompanyActivationJob($account));
        }
    }
}
