<?php

namespace App\GraphQL\Queries;

use App\Models\ApplicantBankingAccess;
use App\Models\ApplicantIndividual;
use App\Models\Role;

class ApplicantBankingAccessQuery
{
    public function enabled($_, array $args)
    {
        $applicantBankingAccess = ApplicantBankingAccess::query()
            ->get()->toArray();
        $bankingAccess = json_decode(json_encode($applicantBankingAccess), true);

        foreach ($bankingAccess as &$access) {
            $permissions = $this->getApplicantPermissions($access['applicant_individual_id']);

            $permissions['create_payments'] == true ? $access += ['create_payments' => true] : $access += ['create_payments' => false];
            $permissions['sign_payments'] == true ? $access += ['sign_payments' => true] : $access += ['sign_payments' => false];
        }

        $result = ApplicantBankingAccess::hydrate($bankingAccess)->paginate( $args['first'] ?? env('PAGINATE_DEFAULT_COUNT'), $args['page'] ?? 1);

        return [
            'data' => $result,
            'paginatorInfo' => [
                'count' => $result->count(),
                'currentPage' => $result->currentPage(),
                'firstItem' => $result->firstItem(),
                'hasMorePages' => $result->hasMorePages(),
                'lastItem' => $result->lastItem(),
                'lastPage' => $result->lastPage(),
                'perPage' => $result->perPage(),
                'total' => $result->total(),
            ]
        ];
    }

    public function get($_, array $args)
    {
        $applicantBankingAccess = ApplicantBankingAccess::find($args['id']);

        $permissions = $this->getApplicantPermissions($applicantBankingAccess['applicant_individual_id']);
        $permissions['create_payments'] == true ? $applicantBankingAccess['create_payments'] = true : $applicantBankingAccess['create_payments'] = false;
        $permissions['sign_payments'] == true ? $applicantBankingAccess['sign_payments'] = true : $applicantBankingAccess['sign_payments'] = false;

        return $applicantBankingAccess;
    }

    public function getApplicantPermissions ($id)
    {
        $result = ['create_payments' => false, 'sign_payments' => false];
        $applicant = ApplicantIndividual::find($id);
        $groupRole = $applicant->groupRole()->first();
        if ($groupRole) {
            $role = Role::find($groupRole->role_id);
        } else {
            return $result;
        }

        $permissions = $role->permissions()->get();

        foreach ($permissions as $permission) {
            if ($permission['upname'] == 'CREATE_PAYMENTS') {
                $result['create_payments'] = true;
            }
            if ($permission['upname'] == 'SIGN_PAYMENTS') {
                $result['sign_payments'] = true;
            }
        }

        return $result;
    }
}
