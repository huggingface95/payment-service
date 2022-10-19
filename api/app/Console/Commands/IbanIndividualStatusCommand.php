<?php

namespace App\Console\Commands;

use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\AccountState;
use App\Services\EmailService;
use Illuminate\Console\Command;

class IbanIndividualStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iban:individual:approval:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email for approval iban status';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @throws GraphqlException
     */
    public function handle(EmailService $emailService)
    {
        $individualIbanAccountApprovals = Account::query()
            ->where('account_state_id', AccountState::WAITING_FOR_APPROVAL)
            ->whereHas('applicantIndividual')
            ->get();

        /** @var Account $account */
        foreach ($individualIbanAccountApprovals as $account) {
            try {
                $emailService->sendAccountStatusEmail($account);
                $account->account_state_id = AccountState::ACTIVE;
                $account->saveQuietly();
            } catch (\Exception) {
                continue;
            }
        }
    }
}
