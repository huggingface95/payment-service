<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class DepartmentsQueryTest extends TestCase
{
    /**
     * Department Query Testing
     *
     * @return void
     */

    public function testDepartmentsNoAuth(): void
    {
        $this->graphQL('
             {
                departments
                 {
                    data {
                        id
                        name
                    }
                }
             }')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryDepartmentById(): void
    {
        $department = DB::connection('pgsql_test')
            ->table('departments')
            ->orderBy('id')
            ->first();

        $this->postGraphQL([
            'query' => '
                query Department($id:ID!){
                    department(id: $id) {
                        id
                    }
                }',
            'variables' => [
                'id' => (string) $department->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJson([
            'data' => [
                'department' => [
                    'id' => (string) $department->id,
                ],
            ],
        ]);
    }

    public function testQueryDepartmentOrderBy(): void
    {
        $department = DB::connection('pgsql_test')
            ->table('departments')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                query {
                    departments(orderBy: { column: ID, order: DESC }) {
                        data {
                            id
                            name
                        }
                    }
                }',
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]
        )->seeJsonContains([
            [
                'id' => (string) $department[0]->id,
                'name' => (string) $department[0]->name,
            ],
        ]);
    }

    public function testQueryDepartmentsByName(): void
    {
        $department = DB::connection('pgsql_test')
            ->table('departments')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                query Department($name: Mixed){
                    departments(
                        filter: { column: NAME, operator: ILIKE, value: $name }
                    ) {
                        data {
                            id
                            name
                        }
                    }
                }',
            'variables' => [
                'name' => $department[0]->name,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            [
                'id' => (string) $department[0]->id,
                'name' => (string) $department[0]->name,
            ],
        ]);
    }

    public function testQueryDepartmentsByCompanyId(): void
    {
        $department = DB::connection('pgsql_test')
            ->table('departments')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                query Departments($id: Mixed) {
                    departments(
                        filter: { column:HAS_COMPANY_FILTER_BY_ID,value: $id}
                    ) {
                        data {
                            id
                            name
                        }
                    }
                }',
            'variables' => [
                'id' => (string) $department[0]->company_id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            [
                'id' => (string) $department[0]->id,
                'name' => (string) $department[0]->name,
            ],
        ]);
    }

    public function testQueryDepartmentPositionById(): void
    {
        $departmentPosition = DB::connection('pgsql_test')
            ->table('department_position')
            ->orderBy('id')
            ->first();

        $this->postGraphQL([
            'query' => '
                query DepartmentPosition($id:ID!){
                    departmentPosition(id: $id) {
                        id
                    }
                }',
            'variables' => [
                'id' => (string) $departmentPosition->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJson([
            'data' => [
                'departmentPosition' => [
                    'id' => (string) $departmentPosition->id,
                ],
            ],
        ]);
    }

    public function testQueryDepartmentPositionsOrderBy(): void
    {
        $departmentPositions = DB::connection('pgsql_test')
            ->table('department_position')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                query {
                    departmentPositions(orderBy: { column: ID, order: DESC }) {
                        data {
                            id
                            name
                        }
                    }
                }',
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            [
                'id' => (string) $departmentPositions[0]->id,
                'name' => (string) $departmentPositions[0]->name,
            ],
        ]);
    }

    public function testQueryDepartmentPositionsHasDepartment(): void
    {
        $departmentPositions = DB::connection('pgsql_test')
            ->table('department_position')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL([
            'query' => '
                query DepartmentPositions($id: Mixed) {
                    departmentPositions(hasDepartment: { column: ID, value: $id }) {
                        data {
                            id
                            name
                        }
                   }
                }',
            'variables' => [
                'id' => $departmentPositions[0]->id
            ]
        ], [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            [
                'id' => (string) $departmentPositions[0]->id,
                'name' => (string) $departmentPositions[0]->name,
            ],
        ]);
    }

    public function testQueryDepartmentPositionsIsActive(): void
    {
        $departmentPositions = DB::connection('pgsql_test')
            ->table('department_position')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL([
            'query' => '
                query {
                    departmentPositions(filter: { column: IS_ACTIVE, value: true }) {
                        data {
                            id
                            name
                        }
                    }
                }',
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            [
                'id' => (string) $departmentPositions[0]->id,
                'name' => (string) $departmentPositions[0]->name,
            ],
        ]);
    }
}
