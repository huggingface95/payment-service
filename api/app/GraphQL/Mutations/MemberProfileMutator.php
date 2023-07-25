<?php

namespace App\GraphQL\Mutations;

use App\DTO\Email\Request\EmailMembersRequestDTO;
use App\DTO\TransformerDTO;
use App\Enums\EmailVerificationStatusEnum;
use App\Exceptions\GraphqlException;
use App\Models\Members;
use App\Services\EmailService;
use App\Services\VerifyService;
use Illuminate\Support\Facades\Hash;

class MemberProfileMutator extends BaseMutator
{
    public function __construct(
        protected EmailService $emailService,
        protected VerifyService $verifyService
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
        /** @var Members $member */
        $member = auth()->user();

        if (isset($args['email']) && $member->email != $args['email']) {
            $this->sendConfirmChangeEmail(null, $args);
            $member->email_verification = EmailVerificationStatusEnum::REQUESTED->value;
        }
        $member->update($args);

        return $member;
    }

    public function sendConfirmChangeEmail($_, array $args)
    {
        $member = auth()->user();

        try {
            $verifyToken = $this->verifyService->createVerifyToken($member);

            $confirmUrl = 'https://dev.admin.docudots.com';
            // TODO: Create Email Template with subject 'Confirm change email'
            $emailTemplateSubject = 'Confirm change email';
            $emailData = [
                'client_name' => $member->first_name,
                'email_confirm_url' => $confirmUrl.'/email/change/verify/'.$verifyToken->token.'?email='.$args['email'],
            ];
            $data = array_merge($args, $emailData);
            $emailDTO = TransformerDTO::transform(EmailMembersRequestDTO::class, $member, $data, $emailTemplateSubject);

            $this->emailService->sendMemberEmailByMemberDto($emailDTO);

            return [
                'status' => 'OK',
                'message' => 'Email sent for processing',
            ];
        } catch (\Throwable $e) {
            throw new GraphqlException($e->getMessage(), 'Internal', $e->getCode());
        }
    }

    public function changeMemberPassword($_, array $args)
    {
        $member = auth()->user();

        $this->checkCurrentPassword($args, $member);

        $member->update(['password_hash'=>Hash::make($args['password']), 'password_salt'=>Hash::make($args['password_confirmation'])]);

        return $member;
    }

    private function checkCurrentPassword(array $args, Members $member)
    {
        if (! Hash::check($args['old_password'], $member->password_hash)) {
            throw new GraphqlException('The current password is wrong', 'use');
        }
    }
}
