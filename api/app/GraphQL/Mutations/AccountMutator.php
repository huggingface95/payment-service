<?php

namespace App\GraphQL\Mutations;

use App\DTO\GraphQLResponse\AccountGenerateIbanResponse;
use App\DTO\TransformerDTO;
use App\Enums\AccountTypeEnum;
use App\Enums\ApplicantTypeEnum;
use App\Enums\GroupTypeEnum;
use App\Exceptions\EmailException;
use App\Exceptions\GraphqlException;
use App\Jobs\Redis\IbanIndividualActivationJob;
use App\Models\Account;
use App\Models\AccountState;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\EmailNotification;
use App\Models\GroupRole;
use App\Models\Groups;
use App\Models\Members;
use App\Models\PaymentProvider;
use App\Services\EmailService;
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
    public function create($root, array $args)
    {
        try {
            DB::beginTransaction();

            $args['member_id'] = Auth::user()->id;

            $company = Members::find($args['member_id'])->company()?->first();

            $groupRoleIds = GroupRole::where('company_id', $company->id)->pluck('id');

            $notifications = EmailNotification::where('company_id', $company->id)
                ->whereIn('group_role_id', $groupRoleIds)
                ->get();

            $args['account_type'] = $this->setAccountType($args['group_type_id']);
            if (empty($args['account_number'])) {
                $args['account_state_id'] = AccountState::WAITING_FOR_ACCOUNT_GENERATION;
            } else {
                $args['account_state_id'] = AccountState::WAITING_FOR_APPROVAL;
            }

            if (isset($args['client_id'])) {
                $args['client_type'] = $args['group_type_id'] == GroupTypeEnum::COMPANY->value ? ApplicantTypeEnum::COMPANY->toString() : ApplicantTypeEnum::INDIVIDUAL->toString();
                if ($args['group_type_id'] == GroupTypeEnum::INDIVIDUAL->value) {
                    ApplicantIndividual::findOrFail($args['client_id']);
                    $args['owner_id'] = $args['client_id'];
                } elseif ($args['group_type_id'] == GroupTypeEnum::COMPANY->value) {
                    $applicantCompany = ApplicantCompany::query()->findOrFail($args['client_id']);
                    $applicantIndividual = $applicantCompany->owner;
                    if ($applicantIndividual) {
                        $args['owner_id'] = $applicantIndividual->id;
                    } else {
                        throw new GraphqlException('Applicant not found for this corporate.', 'Internal', 404);
                    }
                }
            } else {
                throw new GraphqlException('client_id parameter not found.', 'Internal', 400);
            }

            $paymentProvider = PaymentProvider::findOrFail($args['payment_provider_id']);
            if ($paymentProvider->name == PaymentProvider::NAME_INTERNAL) {
                throw new GraphqlException('Creating an account with the Internal payment provider is not allowed.');
            }

            /** @var Account $account */
            $account = Account::query()->create($args);

            if ($notifications) {
                $this->emailService->sendAccountStatusEmail($account);
            }

            if ($account->isParent() && $account->account_number == null && $account->group->name == Groups::INDIVIDUAL) {
                dispatch(new IbanIndividualActivationJob($account));
            }

            DB::commit();

            return $account;
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
