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

    public function testCreateDepartment(): void
    {
        $this->login();

        $seq = DB::table('departments')->max('id') + 1;
        DB::select('ALTER SEQUENCE departments_id_seq RESTART WITH ' . $seq);

        $this->graphQL('
            mutation CreateDepartment($name: String!, $company_id: ID!, $dep_pos:[String]) {
                createDepartment(
                    name: $name
                    company_id: $company_id
                    department_positions_name: $dep_pos
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
                'Director',
                'Manager',
                'Programmer',
            ],
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
        $this->login();

        $seq = DB::table('department_position')->max('id') + 1;
        DB::select('ALTER SEQUENCE department_position_id_seq RESTART WITH ' . $seq);

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
            'name' => 'Test Department Position',
            'company_id' => 1,
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

    public function testDeleteDepartment(): void
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

    public function testDeleteDepartmentPosition(): void
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
