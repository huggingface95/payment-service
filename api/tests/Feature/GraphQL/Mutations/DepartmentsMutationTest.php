<?php

namespace Tests;

use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentsMutationTest extends TestCase
{
    /**
     * Department Mutation Testing
     *
     * @return void
     */

    public function testCreateDepartmentNoAuth(): void
    {
        $seq = DB::table('departments')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE departments_id_seq RESTART WITH ' . $seq);

        $this->graphQL('
            mutation CreateDepartment($name: String!, $company_id: ID!, $dep_pos:[ID]) {
                createDepartment(
                    name: $name
                    company_id: $company_id
                    department_positions_id: $dep_pos
                ) {
                    id
                    name
                    positions {
                        name
                    }
                }
            }
        ', [
            'name' => 'Test Department',
            'company_id' => 1,
            'dep_pos' => [
                1,
                2,
            ],
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateDepartment(): void
    {
        $seq = DB::table('departments')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE departments_id_seq RESTART WITH ' . $seq);

        $this->postGraphQL([
            'query' => '
                mutation CreateDepartment($name: String!, $company_id: ID!, $dep_pos:[ID]) {
                    createDepartment(
                        name: $name
                        company_id: $company_id
                        department_positions_id: $dep_pos
                    ) {
                        id
                        name
                        positions {
                            name
                        }
                    }
                }',
            'variables' => [
                'name' => 'Test Department',
                'company_id' => 1,
                'dep_pos' => [
                    1,
                    2,
                ],
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        $id = json_decode($this->response->getContent(), true);

        $department = Department::latest('id')->first();
        $departmens = $department->positions()->get()->map(function ($department) {
            return $department->only(['name']);
        });

        $this->seeJson([
            'data' => [
                'createDepartment' => [
                    'id' => $id['data']['createDepartment']['id'],
                    'name' => $id['data']['createDepartment']['name'],
                    'positions' => $departmens,
                ],
            ],
        ]);
    }

    public function testCreateDepartmentPosition(): void
    {
        $seq = DB::table('department_position')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE department_position_id_seq RESTART WITH ' . $seq);

        $this->postGraphQL([
            'query' => '
                mutation CreateDepartmentPosition($name: String!, $company_id: ID!) {
                    createDepartmentPosition(
                        name: $name
                        company_id: $company_id
                    ) {
                        id
                        name
                    }
                }',
            'variables' => [
                'name' => 'Test Department Position',
                'company_id' => 1,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createDepartmentPosition' => [
                    'id' => $id['data']['createDepartmentPosition']['id'],
                    'name' => $id['data']['createDepartmentPosition']['name'],
                ],
            ],
        ]);
    }

    public function testUpdateDepartment(): void
    {
        $department = DB::connection('pgsql_test')
            ->table('departments')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                mutation UpdateDepartment($id: ID!, $name: String) {
                    updateDepartment(id: $id, name: $name) {
                        id
                        name
                    }
                }',
            'variables' => [
                'id' => strval($department[0]->id),
                'name' => 'Updated department',
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateDepartment' => [
                    'id' => $id['data']['updateDepartment']['id'],
                    'name' => $id['data']['updateDepartment']['name'],
                ],
            ],
        ]);
    }

    public function testDeleteDepartment(): void
    {
        $department = DB::connection('pgsql_test')
            ->table('departments')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                mutation DeleteDepartment(
                    $id: ID!
                )
                {
                    deleteDepartment (
                        id: $id
                    )
                    {
                        id
                    }
                }',
            'variables' => [
                'id' => (string) $department[0]->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'deleteDepartment' => [
                    'id' => $id['data']['deleteDepartment']['id'],
                ],
            ],
        ]);
    }

    public function testDeleteDepartmentPosition(): void
    {
        $departmentPosition = DB::connection('pgsql_test')
            ->table('department_position')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                mutation DeleteDepartmentPosition(
                    $id: ID!
                )
                {
                    deleteDepartmentPosition (
                        id: $id
                    )
                    {
                        id
                    }
                }',
            'variables' => [
                'id' => (string) $departmentPosition[0]->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'deleteDepartmentPosition' => [
                    'id' => $id['data']['deleteDepartmentPosition']['id'],
                ],
            ],
        ]);
    }
}
