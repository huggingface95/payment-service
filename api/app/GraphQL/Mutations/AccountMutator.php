<?php

namespace App\GraphQL\Mutations;

use App\DTO\GraphQLResponse\AccountGenerateIbanResponse;
use App\DTO\TransformerDTO;
use App\Enums\AccountTypeEnum;
use App\Enums\ApplicantTypeEnum;
use App\Exceptions\EmailException;
use App\Exceptions\GraphqlException;
use App\Jobs\Redis\IbanIndividualActivationJob;
use App\Models\Account;
use App\Models\AccountState;
use App\Models\GroupRole;
use App\Models\Groups;
use App\Services\EmailService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountMutator
{
    public EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * @throws EmailException
     * @throws GraphqlException
     */
    public function create($root, array $args): LengthAwarePaginator
    {
        try {
            DB::beginTransaction();

            $args['member_id'] = Auth::user()->id;

            $args['account_type'] = $this->setAccountType($args['group_type_id']);
            if (!isset($args['account_number'])) {
                $args['account_state_id'] = AccountState::WAITING_FOR_ACCOUNT_GENERATION;
            } else {
                $args['account_state_id'] = AccountState::WAITING_FOR_APPROVAL;
            }

            if (isset($args['client_id'])) {
                if (AccountTypeEnum::BUSINESS == $args['account_type']) {
                    $args['client_type'] = ApplicantTypeEnum::COMPANY->toString();
                } else {
                    $args['client_type'] = ApplicantTypeEnum::INDIVIDUAL->toString();
                }
            }

            /** @var Account $account */
            $account = Account::query()->create($args);


            $this->emailService->sendAccountStatusEmail($account);

            if ($account->isParent() && $account->account_number == null && $account->group->name == Groups::INDIVIDUAL) {
                dispatch(new IbanIndividualActivationJob($account));
            }

            DB::commit();

            if (isset($args['query'])) {
                return Account::getAccountFilter($args['query'])->paginate(env('PAGINATE_DEFAULT_COUNT'));
            } else {
                return Account::paginate(env('PAGINATE_DEFAULT_COUNT'));
            }
        } catch (EmailException $e) {
            DB::rollBack();
            throw new GraphqlException($e->getMessage(), $e->getCode());
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new GraphqlException($e->getMessage(), $e->getCode());
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

    protected function setAccountType(int $groupId): ?string
    {
        if ($groupId == GroupRole::INDIVIDUAL) {
            return AccountTypeEnum::PRIVATE->value;
        } elseif ($groupId == GroupRole::COMPANY) {
            return AccountTypeEnum::BUSINESS->value;
        }
        return null;
    }
}
