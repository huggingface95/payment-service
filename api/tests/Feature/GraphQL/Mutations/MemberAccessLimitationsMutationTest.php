<?php

namespace Feature\GraphQL\Mutations;

use App\Models\MemberAccessLimitation;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MemberAccessLimitationsMutationTest extends TestCase
{
    /**
     * MemberAccessLimitations Mutation Testing
     *
     * @return void
     */
    public function testCreateMemberAccessLimitations(): void
    {
        $seq = DB::table('member_access_limitations')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE member_access_limitations_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateMemberAccessLimitation(
                $member_id: ID!,
                $group_type_id: ID,
                $company_id: ID!,
                $module_id: ID!,
                $project_id: ID,
                $payment_provider_id: ID
            ) {
                createMemberAccessLimitation(input: {
                    member_id: $member_id,
                    group_type_id: $group_type_id,
                    company_id: $company_id,
                    module_id: $module_id,
                    project_id: $project_id,
                    payment_provider_id: $payment_provider_id
                    see_own_applicants: true
                }) {
                    id
                }
            }
        ', [
            'member_id' => 3,
            'group_type_id' => 1,
            'company_id' => 1,
            'module_id' => 2,
            'project_id' => 1,
            'payment_provider_id' => 1,
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['createMemberAccessLimitation']['id'],
            ],
        ]);
    }

    public function testUpdateMemberAccessLimitations(): void
    {
        $accessLimitation = MemberAccessLimitation::orderBy('id', 'DESC')->first();

        $this->graphQL('
            mutation UpdateMemberAccessLimitation(
                $id: ID!
                $member_id: ID!,
                $group_type_id: ID,
                $company_id: ID!,
                $module_id: ID!,
                $project_id: ID,
                $payment_provider_id: ID
            ) {
                updateMemberAccessLimitation(id: $id, input: {
                    member_id: $member_id,
                    group_type_id: $group_type_id,
                    company_id: $company_id,
                    module_id: $module_id,
                    project_id: $project_id,
                    payment_provider_id: $payment_provider_id
                    see_own_applicants: true
                }) {
                    id
                }
            }
        ', [
            'id' => $accessLimitation->id,
            'member_id' => 3,
            'group_type_id' => 1,
            'company_id' => 1,
            'module_id' => 2,
            'project_id' => 1,
            'payment_provider_id' => 1,
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['updateMemberAccessLimitation']['id'],
            ],
        ]);
    }

    public function testDeleteMemberAccessLimitations(): void
    {
        $accessLimitation = MemberAccessLimitation::orderBy('id', 'DESC')->first();

        $this->graphQL('
            mutation DeleteMemberAccessLimitation(
                $id: ID!
            ) {
                deleteMemberAccessLimitation(
                    id: $id
                ) {
                    id
                }
            }
        ', [
            'id' => $accessLimitation->id,
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['deleteMemberAccessLimitation']['id'],
            ],
        ]);
    }
}
