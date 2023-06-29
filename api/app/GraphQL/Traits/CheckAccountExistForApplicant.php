<?php

namespace App\GraphQL\Traits;

use App\Enums\ApplicantTypeEnum;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait CheckAccountExistForApplicant
{

    /**
     * @throws GraphqlException
     */
    public function checkExistsAccountById(int $id): void
    {
        $this->checkExistsAccount(Account::query()->where('id', '=', $id));
    }

    public function checkExistsAccounts(): void
    {
        $this->checkExistsAccount(Account::query()->where('id', '=', $id));
    }

    /**
     * @throws GraphqlException
     */
    public function checkExistsAccountByAccountNumber(string $accountNumber): void
    {
        $this->checkExistsAccount(Account::query()->where('account_number', '=', $accountNumber));
    }

    /**
     * @throws GraphqlException
     */
    private function checkExistsAccount(Builder $accountBuilder): void
    {
        if (!$accountBuilder
            ->where(function (Builder $q) {
                $q->whereHasMorph('clientable', [ApplicantTypeEnum::INDIVIDUAL->toString()], function (Builder $q) {
                    return $q->where('client_id', Auth::user()->id);
                })->orWhere('owner_id', '=', Auth::user()->id);
            })
            ->exists()) {
            throw new GraphqlException('Account not found', 'not found', 404);
        }
    }

}
