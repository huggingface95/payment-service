<?php

namespace App\Jobs;

use App\Models\AccountState;
use App\Models\CompanyModule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateOrRestoreAccountStateJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected Collection $accounts;
    protected CompanyModule $companyModule;

    public function __construct(CompanyModule $companyModule, Collection $accounts)
    {
        $this->accounts = $accounts;
        $this->companyModule = $companyModule;
    }

    public function handle(): void
    {
        try {
            DB::beginTransaction();
            if ($this->companyModule->is_active === false) {
                foreach ($this->accounts as $account) {
                    $account->update(['account_state_id' => AccountState::SUSPENDED]);
                }
            } else {
                foreach ($this->accounts as $account) {
                    $account->restoreLast();
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::log('error', $e->getMessage());
            $this->companyModule->is_active = !$this->companyModule->is_active;
            $this->companyModule->saveQuietly();
        }
    }
}
