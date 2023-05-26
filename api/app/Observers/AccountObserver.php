<?php

namespace App\Observers;

use App\Exceptions\EmailException;
use App\Models\Account;
use App\Models\Currencies;
use App\Services\AccountService;
use App\Services\EmailService;
use Illuminate\Database\Eloquent\Model;

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

        return true;
    }

    public function created(Account|Model $model, bool $callHistory = false): bool
    {
        parent::created($model);

        if (isset($model->parent_id)) {
            $this->accountService->cloneParentAccountMorphRecords($model, $model->parent_id);
        }

        return true;
    }

    public function updating(Account|Model $model, bool $callHistory = false): bool
    {
        if (!parent::updating($model)) {
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
}
