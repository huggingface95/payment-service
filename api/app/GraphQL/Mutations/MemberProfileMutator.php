<?php

namespace App\GraphQL\Mutations;

use App\DTO\Email\Request\EmailMemberRequestDTO;
use App\DTO\TransformerDTO;
use App\Enums\ClientTypeEnum;
use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\BaseMutator;
use App\Models\Account;
use App\Models\EmailVerification;
use App\Services\EmailService;
use Illuminate\Support\Str;

class MemberProfileMutator extends BaseMutator
{
    public function __construct(
        protected EmailService $emailService
    ) {
    }
    /**
     * Return a value for the field.
     *
     * @param  @param  null  $root Always null, since this field has no parent.
     * @param  array<string, mixed>  $args The field arguments passed by the client.
     * @return mixed
     */
    public function update($root, array $args)
    {
        $member = auth()->user();

        if ($args['email']) {
            $this->sendConfirmChangeEmail(null, $args);
        }

        $member->update($args);

        return $member;
    }

    public function sendConfirmChangeEmail($_, array $args)
    {
        $member = auth()->user();

        try {
                $account = Account::where('member_id', $member->id)
                ->firstOrFail();
                $verifyToken = EmailVerification::create([
                    'client_id' => $member->id,
                    'type' => ClientTypeEnum::MEMBER->toString(),
                    'token' => Str::random(64),
                ]);
                $confirmUrl = 'https://dev.admin.docudots.com';
                // TODO: Create Email Template with subject 'Confirm change email'
                $emailTemplateSubject = 'Confirm change email';
                $emailData = [
                    'client_name' => $member->first_name,
                    'email_confirm_url' => $confirmUrl . '/email/verify/' . $verifyToken->token.'?email='.$args['email'],
                ];
                $data = array_merge($args, $emailData);
                $emailDTO = TransformerDTO::transform(EmailMemberRequestDTO::class, $account, $data, $emailTemplateSubject);

                $this->emailService->sendApplicantEmailByApplicantDto($emailDTO);

                return [
                    'status' => 'OK',
                    'message' => 'Email sent for processing',
                ];
        } catch (\Throwable $e) {
            throw new GraphqlException($e->getMessage(), 'Internal', $e->getCode());
        }
    }

}
