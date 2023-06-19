<?php

namespace Feature\GraphQL\Queries;

use App\Models\MemberAccessLimitation;
use Tests\TestCase;

class MemberAccessLimitationsQueryTest extends TestCase
{
    public function testQueryMemberAccessLimitationNoAuth(): void
    {
        $this->graphQL('
            {
                memberAccessLimitation (id: 1) {
                          id
                          company {
                            id
                            name
                          }
                          member {
                            id
                            email
                          }
                          module {
                            id
                            name
                          }
                          group {
                            id
                            name
                          }
                          group_roles {
                            id
                            name
                          }
                          project {
                            id
                            name
                          }
                          provider {
                            id
                            name
                          }
                          see_own_applicants
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryMemberAccessLimitation(): void
    {
        $accessLimitation = MemberAccessLimitation::orderBy('id', 'ASC')
            ->first();

        $company = $accessLimitation->company()->first();
        $module = $accessLimitation->module()->first();
        $group = $accessLimitation->group()->first();
        $group_roles = $accessLimitation->groupRoles()->first();
        $project = $accessLimitation->project()->first();
        $provider = $accessLimitation->provider()->first();

        $this->postGraphQL(
            [
                'query' => '
                query MemberAccessLimitation($id: ID) {
                    memberAccessLimitation(id: $id) {
                          id
                          company {
                            id
                            name
                          }
                          module {
                            id
                            name
                          }
                          group {
                            id
                            name
                          }
                          group_roles {
                            id
                            name
                          }
                          project {
                            id
                            name
                          }
                          provider {
                            id
                            name
                          }
                          see_own_applicants
                    }
                }',
                'variables' => [
                    'id' => $accessLimitation->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'memberAccessLimitation' => [
                    'id' => (string) $accessLimitation->id,
                    'company' => [
                        'id' => (string) $company->id,
                        'name' => (string) $company->name,
                    ],
                    'module' => [
                        'id' => (string) $module->id,
                        'name' => (string) $module->name,
                    ],
                    'group' => [
                        'id' => (string) $group->id,
                        'name' => (string) $group->name,
                    ],
                    'group_roles' => [[
                        'id' => (string) $group_roles->id,
                        'name' => (string) $group_roles->name,
                    ]],
                    'project' => [
                        'id' => (string) $project->id,
                        'name' => (string) $project->name,
                    ],
                    'provider' => [
                        'id' => (string) $provider->id,
                        'name' => (string) $provider->name,
                    ],
                    'see_own_applicants' => $accessLimitation->see_own_applicants,
                ],
            ],
        ]);
    }

    public function testQueryMemberAccessLimitations(): void
    {
        $accessLimitation = MemberAccessLimitation::orderBy('id', 'ASC')
            ->where('member_id', 4)
            ->first();

        $company = $accessLimitation->company()->first();
        $module = $accessLimitation->module()->first();
        $group = $accessLimitation->group()->first();
        $group_roles = $accessLimitation->groupRoles()->first();
        $project = $accessLimitation->project()->first();
        $provider = $accessLimitation->provider()->first();

        $this->postGraphQL(
            [
                'query' => '
                query MemberAccessLimitations($id: ID!) {
                    memberAccessLimitations(member_id: $id) {
                        data {
                          id
                          company {
                            id
                            name
                          }
                          module {
                            id
                            name
                          }
                          group {
                            id
                            name
                          }
                          group_roles {
                            id
                            name
                          }
                          project {
                            id
                            name
                          }
                          provider {
                            id
                            name
                          }
                          see_own_applicants
                        }
                    }
                }',
                'variables' => [
                    'id' => $accessLimitation->member_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'memberAccessLimitations' => [
                    'data' => [[
                        'id' => (string) $accessLimitation->id,
                        'company' => [
                            'id' => (string) $company->id,
                            'name' => (string) $company->name,
                        ],
                        'module' => [
                            'id' => (string) $module->id,
                            'name' => (string) $module->name,
                        ],
                        'group' => [
                            'id' => (string) $group->id,
                            'name' => (string) $group->name,
                        ],
                        'group_roles' => [[
                            'id' => (string) $group_roles->id,
                            'name' => (string) $group_roles->name,
                        ]],
                        'project' => [
                            'id' => (string) $project->id,
                            'name' => (string) $project->name,
                        ],
                        'provider' => [
                            'id' => (string) $provider->id,
                            'name' => (string) $provider->name,
                        ],
                        'see_own_applicants' => $accessLimitation->see_own_applicants,
                    ]],
                ],
            ],
        ]);
    }

    public function testQueryMemberAccessLimitationsWithFilterByGroupRoleId(): void
    {
        $accessLimitation = MemberAccessLimitation::orderBy('id', 'ASC')
            ->where('member_id', 4)
            ->first();

        $company = $accessLimitation->company()->first();
        $module = $accessLimitation->module()->first();
        $group = $accessLimitation->group()->first();
        $group_roles = $accessLimitation->groupRoles()->first();
        $project = $accessLimitation->project()->first();
        $provider = $accessLimitation->provider()->first();

        $this->postGraphQL(
            [
                'query' => '
                query MemberAccessLimitations($id: Mixed, $member_id: ID!) {
                    memberAccessLimitations(member_id: $member_id, filter: {
                        column: HAS_GROUP_ROLES_FILTER_BY_ID, operator: EQ, value: $id
                    }) {
                        data {
                          id
                          company {
                            id
                            name
                          }
                          module {
                            id
                            name
                          }
                          group {
                            id
                            name
                          }
                          group_roles {
                            id
                            name
                          }
                          project {
                            id
                            name
                          }
                          provider {
                            id
                            name
                          }
                          see_own_applicants
                        }
                    }
                }',
                'variables' => [
                    'id' => $group_roles->id,
                    'member_id' => $accessLimitation->member_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'memberAccessLimitations' => [
                    'data' => [[
                        'id' => (string) $accessLimitation->id,
                        'company' => [
                            'id' => (string) $company->id,
                            'name' => (string) $company->name,
                        ],
                        'module' => [
                            'id' => (string) $module->id,
                            'name' => (string) $module->name,
                        ],
                        'group' => [
                            'id' => (string) $group->id,
                            'name' => (string) $group->name,
                        ],
                        'group_roles' => [[
                            'id' => (string) $group_roles->id,
                            'name' => (string) $group_roles->name,
                        ]],
                        'project' => [
                            'id' => (string) $project->id,
                            'name' => (string) $project->name,
                        ],
                        'provider' => [
                            'id' => (string) $provider->id,
                            'name' => (string) $provider->name,
                        ],
                        'see_own_applicants' => $accessLimitation->see_own_applicants,
                    ]],
                ],
            ],
        ]);
    }

    /**
     * @dataProvider provide_testQueryMemberAccessLimitationsWithFilterByCondition
     */
    public function testQueryMemberAccessLimitationsWithFilterByCondition($cond, $value): void
    {
        $accessLimitation = MemberAccessLimitation::orderBy('id', 'ASC')
            ->where('member_id', 4)
            ->where($cond, $value)
            ->first();

        $company = $accessLimitation->company()->first();
        $module = $accessLimitation->module()->first();
        $group = $accessLimitation->group()->first();
        $group_roles = $accessLimitation->groupRoles()->first();
        $project = $accessLimitation->project()->first();
        $provider = $accessLimitation->provider()->first();

        $this->postGraphQL(
            [
                'query' => '
                query MemberAccessLimitations($id: Mixed, $member_id: ID!) {
                    memberAccessLimitations(member_id: $member_id, filter: {
                        column: '.strtoupper($cond).', operator: EQ, value: $id
                    }) {
                        data {
                          id
                          company {
                            id
                            name
                          }
                          module {
                            id
                            name
                          }
                          group {
                            id
                            name
                          }
                          group_roles {
                            id
                            name
                          }
                          project {
                            id
                            name
                          }
                          provider {
                            id
                            name
                          }
                          see_own_applicants
                        }
                    }
                }',
                'variables' => [
                    'id' => $value,
                    'member_id' => $accessLimitation->member_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'memberAccessLimitations' => [
                    'data' => [[
                        'id' => (string) $accessLimitation->id,
                        'company' => [
                            'id' => (string) $company->id,
                            'name' => (string) $company->name,
                        ],
                        'module' => [
                            'id' => (string) $module->id,
                            'name' => (string) $module->name,
                        ],
                        'group' => [
                            'id' => (string) $group->id,
                            'name' => (string) $group->name,
                        ],
                        'group_roles' => [[
                            'id' => (string) $group_roles->id,
                            'name' => (string) $group_roles->name,
                        ]],
                        'project' => [
                            'id' => (string) $project->id,
                            'name' => (string) $project->name,
                        ],
                        'provider' => [
                            'id' => (string) $provider->id,
                            'name' => (string) $provider->name,
                        ],
                        'see_own_applicants' => $accessLimitation->see_own_applicants,
                    ]],
                ],
            ],
        ]);
    }

    public function provide_testQueryMemberAccessLimitationsWithFilterByCondition()
    {
        return [
            ['module_id', '1'],
            ['project_id', '1'],
            ['payment_provider_id', '1'],
            ['group_type_id', '1'],
        ];
    }
}
