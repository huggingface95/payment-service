<?php

namespace App\GraphQL\Mutations;

use App\Enums\MemberStatusEnum;
use App\Exceptions\EmailException;
use App\Exceptions\GraphqlException;
use App\Models\ClientIpAddress;
use App\Models\DepartmentPosition;
use App\Models\GroupRole;
use App\Models\Members;
use App\Services\EmailService;
use App\Services\VerifyService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MembersMutator extends BaseMutator
{
    public function __construct(
        protected EmailService $emailService,
        protected VerifyService $verifyService
    ) {
    }

    /**
     * @param $_
     * @param  array  $args
     * @return mixed
     *
     * @throws GraphqlException
     */
    public function create($_, array $args): mixed
    {
        DB::beginTransaction();
        try {
            if (! isset($args['password'])) {
                $password = Str::random(8);
            } else {
                $password = $args['password'];
            }

            $args['password_hash'] = Hash::make($password);
            $args['password_salt'] = $args['password_hash'];
            $args['is_need_change_password'] = true;

            $member = Members::create($args);

            if (isset($args['group_id'])) {
                $member->groupRole()->sync([$args['group_id']], true);
            }

            if (isset($args['send_email']) && $args['send_email'] === true) {
                $this->emailService->sendChangePasswordEmail($member);
            }
            DB::commit();

            return $member;
        } catch (EmailException $e) {
            DB::rollBack();
            throw new GraphqlException($e->getMessage(), $e->getCode());
        } catch (\Throwable) {
            DB::rollBack();
            throw new GraphqlException('Internal server error', 'internal');
        }
    }

    /**
     * @param $_
     * @param  array  $args
     * @return mixed
     */
    public function update($_, array $args)
    {
        $member = Members::find($args['id']);

        if (isset($args['additional_fields'])) {
            $args['additional_fields'] = $this->setAdditionalField($args['additional_fields']);
        }
        if (isset($args['additional_info_fields'])) {
            $args['additional_info_fields'] = $this->setAdditionalField($args['additional_info_fields']);
        }

        if (isset($args['department_position_id'])) {
            $departamentPosition = DepartmentPosition::find($args['department_position_id']);

            if (! isset($departamentPosition)) {
                throw new GraphqlException('An entry with this id does not exist', 'not found', 404);
            }

            if ($departamentPosition->company->id !== $member->company_id) {
                throw new GraphqlException('Position is not this company', 'use', 409);
            }
        }

        if (isset($args['ip_address'])) {
            $ip_address = str_replace(' ', '', explode(',', $args['ip_address']));
            for ($i = 0; $i < count($ip_address); $i++) {
                if (! filter_var($ip_address[$i], FILTER_VALIDATE_IP)) {
                    throw new GraphqlException('Not a valid ip address. Address format xxx.xxx.xxx.xxx and must be comma separated', 'internal', 403);
                }
            }
            if (count($ip_address) > 0) {
                $member->ipAddress()->delete();
            }
            foreach ($ip_address as $ip) {
                ClientIpAddress::create([
                    'client_id' => $member->id,
                    'ip_address' => $ip,
                    'client_type' => class_basename(Members::class),
                ]);
            }
        }

        $member->update($args);

        if (isset($args['group_id'])) {
            $member->groupRole()->sync([$args['group_id']], true);
        }

        return $member;
    }

    /**
     * @param $_
     * @param  array  $args
     * @return array
     */
    public function setPassword($_, array $args)
    {
        Members::where('id', $args['id'])->update(['password_hash'=>Hash::make($args['password']), 'password_salt'=>Hash::make($args['password_confirmation'])]);

        return $args;
    }

    /**
     * @param    $_
     * @param  array  $args
     * @return mixed
     */
    public function resetPassword($_, array $args)
    {
        $member = Members::find($args['id']);
        if (! $member) {
            throw new GraphqlException('Member not found', 'not found', 404);
        }

        $password = Str::random(8);
        $args['password_hash'] = Hash::make($password);
        $args['password_salt'] = $args['password_hash'];
        $args['is_need_change_password'] = true;
        $member->update($args);

        $this->emailService->sendChangePasswordEmail($member);

        return $member;
    }

    /**
     * @param    $_
     * @param  array  $args
     * @return mixed
     */
    public function setSuspended($_, array $args)
    {
        $member = Members::find($args['id']);
        if (! $member) {
            throw new GraphqlException('Member not found', 'not found', 404);
        }

        $member->update([
            'member_status_id' => MemberStatusEnum::SUSPENDED->value,
        ]);

        return $member;
    }

    /**
     * @param    $_
     * @param  array  $args
     * @return mixed
     */
    public function setInactive($_, array $args)
    {
        $member = Members::find($args['id']);
        if (! $member) {
            throw new GraphqlException('Member not found', 'not found', 404);
        }

        $member->update([
            'member_status_id' => MemberStatusEnum::INACTIVE->value,
        ]);

        $this->emailService->sendVerificationEmail($member);

        return $member;
    }

    /**
     * @param    $_
     * @param  array  $args
     * @return mixed
     */
    public function setActive($_, array $args)
    {
        $member = Members::find($args['id']);
        if (! $member) {
            throw new GraphqlException('Member not found', 'not found', 404);
        }

        $member->update([
            'member_status_id' => MemberStatusEnum::ACTIVE->value,
        ]);

        return $member;
    }

    /**
     * @param    $_
     * @param  array  $args
     * @return mixed
     */
    public function sendEmailVerification($_, array $args)
    {
        $member = Members::find($args['id']);

        $this->emailService->sendVerificationEmail($member);

        return $member;
    }

    /**
     * @param  int  $roleId
     * @return mixed
     */
    private function getMemberGroupRole(int $roleId)
    {
        return GroupRole::where(['group_type_id'=>1, 'role_id' => $roleId])->first();
    }

    public function setSecurityPin($_, array $args)
    {
        Members::where('id', $args['id'])->update(['security_pin'=>str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT)]);

        return $args;
    }
}
