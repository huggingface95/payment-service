<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class DepartmentsTest extends TestCase
{
    /**
     * Department Testing
     *
     * @return void
     */

    public function testCreateDepartment()
    {
        $this->login();

        $seq = DB::table('departments')->max('id') + 1;
        DB::select('ALTER SEQUENCE departments_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateDepartment($name: String!, $company_id: ID!, $dep_pos:[String]) {
                createDepartment(
                    name: $name
                    company_id: $company_id
                    department_positions_name: $dep_pos
                ) {
                    id
                    name
                }
            }
        ', [
            'name' =>  'Test Department',
            'company_id' =>  1,
            'dep_pos' => [
                'Director',
                'Manager',
                'Programmer',
            ]
        ]);
        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createDepartment' => [
                    'id' => $id['data']['createDepartment']['id'],
                    'name' => $id['data']['createDepartment']['name'],
                ],
            ],
        ]);
    }

    public function testCreateDepartmentPosition()
    {
        $this->login();

        $seq = DB::table('department_position')->max('id') + 1;
        DB::select('ALTER SEQUENCE department_position_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateDepartmentPosition($name: String!, $company_id: ID!) {
                createDepartmentPosition(
                    name: $name
                    company_id: $company_id
                ) {
                    id
                    name
                }
            }
        ', [
            'name' =>  'Test Department Position',
            'company_id' =>  1,
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

    public function testUpdateDepartment()
    {
        $this->login();

        $department = DB::connection('pgsql_test')
            ->table('departments')
            ->orderBy('id', 'DESC')
            ->get();

        $this->graphQL('
            mutation UpdateDepartment($id: ID!, $name: String) {
                updateDepartment(id: $id, name: $name) {
                    id
                    name
                }
            }
        ', [
            'id' => strval($department[0]->id),
            'name' => 'Updated department',
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

    public function testQueryDepartmentById()
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

    public function testQueryDepartmentOrderBy()
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

    public function testQueryDepartmentsByName()
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
        'name' => 'Updated Department',
        ])->seeJsonContains([
            [
                'id' => strval($department[0]->id),
                'name' => strval($department[0]->name),
            ],
        ]);
    }

    public function testQueryDepartmentsByCompanyId()
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

    public function testQueryDepartmentPositionById()
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

    public function testQueryDepartmentPositionsOrderBy()
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

    public function testQueryDepartmentPositionsHasDepartment()
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

    public function testQueryDepartmentPositionsIsActive()
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

    public function testDeleteDepartment()
    {
        $this->login();

        $department = DB::connection('pgsql_test')
            ->table('departments')
            ->orderBy('id', 'DESC')
            ->get();

        $this->graphQL('
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
            }
        ', [
            'id' => strval($department[0]->id),
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

    public function testDeleteDepartmentPosition()
    {
        $this->login();

        $departmentPosition = DB::connection('pgsql_test')
            ->table('department_position')
            ->orderBy('id', 'DESC')
            ->get();

        $this->graphQL('
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
            }
        ', [
            'id' => strval($departmentPosition[0]->id),
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
