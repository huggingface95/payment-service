<?php

namespace App\Observers;

use App\Exceptions\EmailException;
use App\Exceptions\GraphqlException;
use App\Jobs\UpdateOrRestoreAccountStateJob;
use App\Models\Account;
use App\Models\Currencies;
use App\Services\EmailService;
use App\Services\AccountService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

class AccountObserver extends BaseObserver
{
    public function __construct(protected AccountService $accountService, protected EmailService $emailService)
    {
    }

    public function creating(Account|Model $model, bool $callHistory = false): bool
    {
        if (!parent::creating($model)) {
            return false;
        }

        if ($model->isChild()) {
            /** @var Currencies $currency */
            $currency = $model->currencies;
            $parent = $model->parent;

            $model->account_number = isset($model->account_number)
                ? sprintf('%s-%s', $model->account_number, $currency->code)
                : sprintf('%s-%s', $parent->account_number, $currency->code);

            $this->accountService->cloneParentAccountColumns($model, $parent->id);
        }

        if ($model->is_primary) {
            /** @var Account $existAccount */
            $existAccount = Account::query()->where('payment_provider_id', $model->payment_provider_id)
                ->where('iban_provider_id', $model->iban_provider_id)
                ->where('client_id', $model->client_id)
                ->where('is_primary', true)
                ->exists();

            if ($existAccount) {
                throw new GraphqlException('This client already has a primary account with the same IBAN and PP', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return true;
    }

    public function created(Account|Model $model, bool $callHistory = false): bool
    {
        parent::created($model);

//        if (isset($model->parent_id)) {
//            $this->accountService->cloneParentAccountMorphRecords($model, $model->parent_id);
//        }

        return true;
    }

    public function saving(Account|Model $model, bool $callHistory = true): bool
    {
        if (!$this->hasCalledClass(UpdateOrRestoreAccountStateJob::class, 'handle') && !$model->isActiveBankingModule()) {
            throw new GraphqlException('Create or Enable Company Banking module in this account', 'use', 401);
        }

        if (!parent::saving($model, $model->exists ? $callHistory : false)) {
            return false;
        }

        return true;
    }

    public function updating(Account|Model $model, bool $callHistory = true): bool
    {
        if (!parent::updating($model, $callHistory)) {
            return false;
        }

        if ($model->isParent()) {
            foreach ($model->children as $child) {
                $this->accountService->cloneParentAccountColumns($child, $model->id);
                $child->save();
            }
        }

        return true;
    }


    /**
     * @throws EmailException
     */
    public function updated(Account|Model $model, bool $callHistory = true): bool
    {
        parent::updated($model, $callHistory);

        if (array_key_exists('account_state_id', $model->getChanges())) {
            $this->emailService->sendAccountStatusEmail($model);
        }

        return true;
    }

    protected function hasCalledClass(string $class, string $method): bool
    {
        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $call) {
            if (isset($call['function']) && isset($call['class']) && $call['class'] == $class && $call['function'] == $method) {
                return true;
            }
        }

        return false;
    }
}
