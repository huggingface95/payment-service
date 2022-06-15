<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\DepartmentPosition;
use App\Models\GroupRole;
use App\Models\Members;
use GraphQL\Exception\InvalidArgument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 *
 */
class MembersMutator extends BaseMutator
{


    /**
     * @param $_
     * @param array $args
     * @return mixed
     */
    public function create($_, array $args)
    {
        if (!isset($args['password'])) {
            $password = Str::random(8);
        } else {
            $password =$args['password'];
        }

        $args['password_hash'] = Hash::make($password);
        $args['password_salt'] = Hash::make($password);

        $member = Members::create($args);

        if (isset($args['group_id'])){
            $member->groupRoles()->sync([$args['group_id']], true);
        }

        return $member;
    }

    /**
     * @param $_
     * @param array $args
     * @return mixed
     */
    public function update($_, array $args)
    {
        $member = Members::find($args['id']);


        if (isset($args['additional_fields'])) {
            $args['additional_fields']  = $this->setAdditionalField($args['additional_fields']);
        }
        if (isset($args['additional_info_fields'])) {
            $args['additional_info_fields']  = $this->setAdditionalField($args['additional_info_fields']);
        }

        if(isset($args['department_position']))
        {
            $departamentPosition = DepartmentPosition::find($args['department_position']);

            if (!isset($departamentPosition)) {
                throw new GraphqlException('An entry with this id does not exist',"not found",404);
            }

            if ($departamentPosition->department->company->id !== $member->company_id) {
                throw new GraphqlException('Position is not this company',"internal",500);
            }

            $member->department_position_id = $args['department_position'];
        }

        if(isset($args['ip_address']))
        {
            $valid_ip = preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}(?:,\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})*$/', $args['ip_address']);
            if (!$valid_ip) {
                throw new GraphqlException('Not a valid ip address',"internal",403);
            }
            $user = DB::select("SELECT client_id FROM client_ip_address WHERE id = ".$member->id);
            if ($user) {
                DB::update("UPDATE client_ip_address SET ip_address = ? WHERE client_id = ?", [$args['ip_address'], $member->id]);
            } else {
               DB::insert("INSERT INTO client_ip_address VALUES (ip_address, client_id) VALUES (?, ?)",[$args['ip_address'],$member->id ]);
            }
        }

        $member->update($args);

        if (isset($args['group_id'])){
            $member->groupRoles()->sync([$args['group_id']], true);
        }

        return $member;
    }

    /**
     * @param $_
     * @param array $args
     * @return mixed
     */
    public function invite($_, array $args)
    {
        $password = Str::random(8);
        $args['is_active'] = false;

        $args['password_hash'] = Hash::make($password);
        $args['password_salt'] = Hash::make($password);

        $member = Members::create($args);

        if (isset($args['group_id'])){
            $member->groupRoles()->sync([$args['group_id']], true);
        }

        return $member;
    }

    /**
     * @param $_
     * @param array $args
     * @return array
     */
    public function setPassword($_, array $args)
    {
        Members::where('id',$args['id'])->update(['password_hash'=>Hash::make($args['password']),'password_salt'=>Hash::make($args['password_confirmation'])]);
        return $args;
    }

    /**
     * @param int $roleId
     * @return mixed
     */
    private function getMemberGroupRole(int $roleId)
    {
        return GroupRole::where(['group_type_id'=>1, 'role_id' => $roleId])->first();
    }

    public function setSecurityPin($_, array $args)
    {
        Members::where('id',$args['id'])->update(['security_pin'=>str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT)]);
        return $args;
    }

}
