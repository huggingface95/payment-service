<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\Project;
use Database\Seeders\ModulesTableSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\ProjectTableSeeder;
use Tests\TestCase;

class ProjectsQueryTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        (new DatabaseSeeder())->call(ModulesTableSeeder::class);
        (new DatabaseSeeder())->call(ProjectTableSeeder::class);
    }

    public function testQueryProject(): void
    {
        $this->login();

        $project = Project::create([
            'name' => 'Test Project',
            'company_id' => 1,
        ]);

        $this->graphQL('
            query Project($id:ID!){
                project(id: $id) {
                    id
                    name
                }
            }
        ', [
            'id' => (string) $project->id,
        ])->seeJson([
            'data' => [
                'project' => [
                    'id' => (string) $project->id,
                    'name' => $project->name,
                ],
            ],
        ]);
    }

    public function testQueryProjects(): void
    {
        $this->login();

        $projects = Project::orderBy('id', 'ASC')->get();

        $expect = [];

        foreach ($projects as $project) {
            $expect['data']['projects']['data'][] = [
                'id' => (string) $project->id,
                'name' => $project->name,
            ];
        }

        $this->graphQL('
            query {
                projects{
                    data {
                        id
                        name
                    }
                }
            }
        ')->seeJson($expect);
    }

    public function testQueryProjectsWithFilterById(): void
    {
        $this->login();

        $projects = Project::where('id', 1)->orderBy('id', 'ASC')->get();

        $expect = [];

        foreach ($projects as $project) {
            $expect['data']['projects']['data'][] = [
                'id' => (string) $project->id,
                'name' => (string) $project['name'],
            ];
        }

        $this->graphQL('
            query Projects($id: Mixed) {
                projects (
                    filter: { column: ID, operator: EQ, value: $id }
                ) {
                    data {
                        id
                        name
                    }
                }
            }
        ', [
            'id' => 1,
        ])->seeJson($expect);
    }

    public function testQueryProjectsWithFilterByModuleId(): void
    {
        $this->login();

        $projects = Project::where('module_id', 1)->orderBy('id', 'ASC')->get();

        $expect = [];

        foreach ($projects as $project) {
            $expect['data']['projects']['data'][] = [
                'id' => (string) $project->id,
                'name' => (string) $project['name'],
            ];
        }

        $this->graphQL('
            query Projects($module_id: Mixed) {
                projects (
                    filter: { column: MODULE_ID, operator: EQ, value: $module_id }
                ) {
                    data {
                        id
                        name
                    }
                }
            }
        ', [
            'module_id' => 1,
        ])->seeJson($expect);
    }

    public function testQueryProjectsWithFilterByCompanyId(): void
    {
        $this->login();

        $projects = Project::where('company_id', 1)->orderBy('id', 'ASC')->get();

        $expect = [];

        foreach ($projects as $project) {
            $expect['data']['projects']['data'][] = [
                'id' => (string) $project->id,
                'name' => (string) $project['name'],
            ];
        }

        $this->graphQL('
            query Projects($company_id: Mixed) {
                projects (
                    filter: { column: COMPANY_ID, operator: EQ, value: $company_id }
                ) {
                    data {
                        id
                        name
                    }
                }
            }
        ', [
            'company_id' => 1,
        ])->seeJson($expect);
    }
}
