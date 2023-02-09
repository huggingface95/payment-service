<?php

namespace App\GraphQL\Mutations;

use App\DTO\GraphQLResponse\AccountGenerateIbanResponse;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Jobs\Redis\IbanIndividualActivationJob;
use App\Models\Account;
use App\Models\AccountState;
use App\Models\GroupRole;
use App\Models\Groups;
use App\Services\EmailService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class AccountMutator
{
    public EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * @throws GraphqlException
     */
    public function create($root, array $args): LengthAwarePaginator
    {
        $args['member_id'] = Auth::user()->id;

        $args['account_type'] = $this->setAccountType($args['group_type_id']);
        if (! isset($args['account_number'])) {
            $args['account_state_id'] = AccountState::WAITING_FOR_ACCOUNT_GENERATION;
        } else {
            $args['account_state_id'] = AccountState::WAITING_FOR_APPROVAL;
        }

        /** @var Account $account */
        $account = Account::query()->create($args);

        if (isset($args['clientableAttach'])) {
            $account->clientableAttach()->sync($args['clientableAttach']['sync']);
        }

        $this->emailService->sendAccountStatusEmail($account);

        if ($account->account_number == null && $account->group->name == Groups::INDIVIDUAL) {
            dispatch(new IbanIndividualActivationJob($account));
        }

        if (isset($args['query'])) {
            return Account::getAccountFilter($args['query'])->paginate(env('PAGINATE_DEFAULT_COUNT'));
        } else {
            return Account::paginate(env('PAGINATE_DEFAULT_COUNT'));
        }
    }

    public function generate($root, array $args)
    {
        /** @var Account $account */
        $account = Account::find($args['id']);

        if ($account->group->name == Groups::INDIVIDUAL) {
            $account->account_state_id = AccountState::AWAITING_ACCOUNT;
            $account->save();

            dispatch(new IbanIndividualActivationJob($account));

            return TransformerDTO::transform(AccountGenerateIbanResponse::class, true);
        }

        return TransformerDTO::transform(AccountGenerateIbanResponse::class);
    }

    protected function setAccountType(int $groupId)
    {
        if ($groupId == GroupRole::INDIVIDUAL) {
            return Account::PRIVATE;
        } elseif ($groupId == GroupRole::COMPANY) {
            return Account::BUSINESS;
        }
    }
}
