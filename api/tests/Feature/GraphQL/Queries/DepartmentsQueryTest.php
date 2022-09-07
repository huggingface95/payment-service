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

    public function testQueryDepartmentById(): void
    {
        $this->login();

        $department = DB::connection('pgsql_test')
            ->table('departments')
            ->orderBy('id')
            ->latest('id')
            ->first();

        $this->graphQL('
            query Department($id:ID!){
                department(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($department->id),
        ])->seeJson([
            'data' => [
                'department' => [
                    'id' => strval($department->id),
                ],
            ],
        ]);
    }

    public function testQueryDepartmentOrderBy(): void
    {
        $this->login();

        $department = DB::connection('pgsql_test')
            ->table('departments')
            ->select('*')
            ->orderBy('id', 'DESC')
            ->get();

        $this->graphQL('
            query {
                departments(orderBy: { column: ID, order: DESC }) {
                    data {
                        id
                        name
                    }
                }
            }
        ')->seeJsonContains([
            [
                'id' => strval($department[0]->id),
                'name' => strval($department[0]->name),
            ],
        ]);
    }

    public function testQueryDepartmentsByName(): void
    {
        $this->login();

        $department = DB::connection('pgsql_test')
            ->table('departments')
            ->select('*')
            ->orderBy('id', 'DESC')
            ->get();

        $this->graphQL('
            query Departments($name: Mixed){
                departments(
                    filter: { column: NAME, operator: ILIKE, value: $name }
                ) {
                    data {
                        id
                        name
                    }
                }
            }
    ', [
        'name' => $department[0]->name,
        ])->seeJsonContains([
            [
                'id' => strval($department[0]->id),
                'name' => strval($department[0]->name),
            ],
        ]);
    }

    public function testQueryDepartmentsByCompanyId(): void
    {
        $this->login();
        $department = DB::connection('pgsql_test')
            ->table('departments')
            ->select('*')
            ->orderBy('id', 'DESC')
            ->get();

        $this->graphQL('
            query {
                departments(
                    filter: { column:HAS_COMPANY_FILTER_BY_ID,value: 1}
                ) {
                    data {
                        id
                        name
                    }
                }
            }
        ')->seeJsonContains([
            [
                'id' => strval($department[0]->id),
                'name' => strval($department[0]->name),
            ],
        ]);
    }

    public function testQueryDepartmentPositionById(): void
    {
        $this->login();

        $departmentPosition = DB::connection('pgsql_test')
            ->table('department_position')
            ->orderBy('id')
            ->first();

        $this->graphQL('
            query DepartmentPosition($id:ID!){
                departmentPosition(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($departmentPosition->id),
        ])->seeJson([
            'data' => [
                'departmentPosition' => [
                    'id' => strval($departmentPosition->id),
                ],
            ],
        ]);
    }

    public function testQueryDepartmentPositionsOrderBy(): void
    {
        $this->login();

        $departmentPositions = DB::connection('pgsql_test')
            ->table('department_position')
            ->select('*')
            ->orderBy('id', 'DESC')
            ->get();

        $this->graphQL('
            query {
                departmentPositions(orderBy: { column: ID, order: DESC }) {
                    data {
                        id
                        name
                    }
                }
            }
        ')->seeJsonContains([
            [
                'id' => strval($departmentPositions[0]->id),
                'name' => strval($departmentPositions[0]->name),
            ],
        ]);
    }

    public function testQueryDepartmentPositionsHasDepartment(): void
    {
        $this->login();

        $departmentPositions = DB::connection('pgsql_test')
            ->table('department_position')
            ->select('*')
            ->orderBy('id', 'ASC')
            ->get();

        $this->graphQL('
            query {
                departmentPositions(hasDepartment: { column: ID, value: 1 }) {
                    data {
                        id
                        name
                    }
               }
            }
        ')->seeJsonContains([
            [
                'id' => strval($departmentPositions[0]->id),
                'name' => strval($departmentPositions[0]->name),
            ],
        ]);
    }

    public function testQueryDepartmentPositionsIsActive(): void
    {
        $this->login();

        $departmentPositions = DB::connection('pgsql_test')
            ->table('department_position')
            ->select('*')
            ->orderBy('id', 'ASC')
            ->get();

        $this->graphQL('
            query {
                departmentPositions(filter: { column: IS_ACTIVE, value: true }) {
                    data {
                        id
                        name
                    }
                }
            }
        ')->seeJsonContains([
            [
                'id' => strval($departmentPositions[0]->id),
                'name' => strval($departmentPositions[0]->name),
            ],
        ]);
    }
}
