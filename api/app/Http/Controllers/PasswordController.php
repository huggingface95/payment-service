<?php

namespace App\Http\Controllers;

use App\DTO\Email\Request\EmailMembersRequestDTO;
use App\DTO\TransformerDTO;
use App\Models\Members;
use App\Services\EmailService;
use App\Services\VerifyService;
use App\Traits\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    use ResetsPasswords;

    public function __construct(
        protected EmailService $emailService,
        protected VerifyService $verifyService
    )
    {
        $this->broker = 'members';
    }

    public function changeMemberPassword(Request $request): JsonResponse
    {
        $rules = [
            'current_password' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];

        $this->validate($request, $rules);

        $member = Members::where('email', $request->email)->where('is_need_change_password', true)->first();
        if (! $member) {
            return response()->json([
                'success' => false,
                'error' => 'Member wich expect to change password not found',
            ]);
        }

        if (!Hash::check($request->current_password, $member->password_hash)) {
            return response()->json([
                'success' => false,
                'error' => 'The current password is wrong',
            ]);
        }

        $data['password_hash'] = Hash::make($request->password);
        $data['password_salt'] = $data['password_hash'];
        $data['is_need_change_password'] = false;

        if ($member->update($data)) {
            $company = $member->company;
            
            $emailTemplateName = 'Successful Password Reset';
            $emailData = [
                'email' => $member->email,
                'member_name' => $member->first_name,
                'member_login_page_url' => $company->companySettings->client_url,
                'member_customer_support_url' => $company->companySettings->support_email,
                'member_company_name' => $company->name,
            ];

            $emailDTO = TransformerDTO::transform(EmailMembersRequestDTO::class, $member, $emailData, $emailTemplateName);
            $this->emailService->sendMemberEmailByMemberDto($emailDTO, true);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function changeMemberPasswordByToken(Request $request): JsonResponse
    {
        $rules = $this->getResetValidationRules();
        unset($rules['token']);

        $this->validate($request, $rules);

        // One-time link valid 24 hours
        $user = $this->verifyService->getVerifyUserModelByToken($request->token, 24);
        $this->verifyService->deleteVerifyCode($request->token);

        if (! $user) {
            return response()->json([
                'success' => false,
                'error' => 'Member not found by token or token is incorrect',
            ]);
        }

        if (! $user->is_need_change_password) {
            return response()->json([
                'success' => false,
                'error' => 'Member wich expect to change password not found',
            ]);
        }

        $data['password_hash'] = Hash::make($request->password);
        $data['password_salt'] = $data['password_hash'];
        $data['is_need_change_password'] = false;

        if ($user->update($data)) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
