<?php

namespace App\GraphQL\Mutations;

use App\DTO\Email\SmtpConfigDTO;
use App\DTO\Email\SmtpDataDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Jobs\Redis\IbanIndividualActivationJob;
use App\Jobs\SendMailJob;
use App\Models\Account;
use App\Models\AccountState;
use App\Models\EmailSmtp;
use App\Models\EmailTemplate;
use App\Models\GroupRole;
use App\Traits\ReplaceRegularExpressions;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class AccountMutator
{
    use ReplaceRegularExpressions;

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
        $account = Account::create($args);
        $account->load('group', 'company', 'paymentProvider', 'clientable', 'owner',
            'accountState', 'paymentBank', 'paymentSystem', 'currencies', 'groupRole'
        );

        $smtp = $this->getSmtp($account);
        $messageData = $this->getTemplateContentAndSubject($account);
        $this->sendEmail($smtp, $messageData);

        if (isset($args['query'])) {
            return Account::getAccountFilter($args['query'])->paginate(env('PAGINATE_DEFAULT_COUNT'));
        } else {
            return Account::paginate(env('PAGINATE_DEFAULT_COUNT'));
        }
    }

    /**
     * @throws GraphqlException
     */
    public function update($root, array $args): Account
    {
        /** @var Account $account */
        $account = Account::find($args['id']);
        $args['account_type'] = $this->setAccountType($args['group_type_id']);

        $account->update($args);

        $account->load('group', 'company', 'paymentProvider', 'clientable', 'owner',
            'accountState', 'paymentBank', 'paymentSystem', 'currencies', 'groupRole'
        );

        if (array_key_exists('account_state_id', $account->getChanges())) {
            $smtp = $this->getSmtp($account);
            $messageData = $this->getTemplateContentAndSubject($account);
            $this->sendEmail($smtp, $messageData);
        }

        return $account;
    }

    public function generate($root, array $args): void
    {
        $account = Account::find($args['id']);
        $account->account_state_id = AccountState::AWAITING_ACCOUNT;
        $account->save();

        dispatch(new IbanIndividualActivationJob($account));
    }

    protected function setAccountType(int $groupId)
    {
        if ($groupId == GroupRole::INDIVIDUAL) {
            return Account::PRIVATE;
        } elseif ($groupId == GroupRole::COMPANY) {
            return Account::BUSINESS;
        }
    }

    /**
     * @throws GraphqlException
     */
    protected function getSmtp(Account $account): EmailSmtp
    {
        $smtp = EmailSmtp::where('member_id', $account->member_id)->where('company_id', $account->company_id)->first();

        if (! $smtp) {
            throw new GraphqlException('SMTP configuration for this company not found', 'Not found', '404');
        }

        try {
            $smtp->replay_to = $account->owner->email;
        } catch (\Throwable) {
            throw new GraphqlException('Проблема может быть связан с Member Access Limitation', 'Not found', '404');
        }

        return $smtp;
    }

    /**
     * @throws GraphqlException
     */
    protected function getTemplateContentAndSubject(Account $account): array
    {
        try {

            /** @var EmailTemplate $emailTemplate */
            $emailTemplate = EmailTemplate::query()
                ->where('member_id', $account->member_id)
                ->where('company_id', $account->company_id)
                ->whereRaw("lower(subject) LIKE  '%".strtolower($account->accountState->name)."%'  ")
                ->first();

            $content = $this->replaceObjectData($emailTemplate->getHtml(), $account, '/\{(.*?)}/');
            $subject = $this->replaceObjectData($emailTemplate->subject, $account, '/\{(.*?)}/');

            return [
                'subject' => $subject,
                'content' => $content,
            ];
        } catch (\Throwable) {
            throw new GraphqlException('Email template error', '404');
        }
    }

    /**
     * @throws GraphqlException
     */
    protected function sendEmail(EmailSmtp $smtp, array $data): void
    {
        try {
            $data = TransformerDTO::transform(SmtpDataDTO::class, $smtp, $data['content'], $data['subject']);
            $config = TransformerDTO::transform(SmtpConfigDTO::class, $smtp);
            dispatch(new SendMailJob($config, $data));
        } catch (\Throwable) {
            throw new GraphqlException('Don\'t send email', '404');
        }
    }
}
