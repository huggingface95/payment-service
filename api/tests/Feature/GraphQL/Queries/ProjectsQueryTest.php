<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProjectsQueryTest extends TestCase
{
    public function testQueryProjectNoAuth(): void
    {
        $this->graphQL('
            {
                projects {
                    data {
                         id
                        name
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryProject(): void
    {
        $project = DB::connection('pgsql_test')
            ->table('projects')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query Project($id:ID!){
                    project(id: $id) {
                        id
                        name
                    }
                }',
                'variables' =>  [
                    'id' => (string) $project->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
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
        $projects = Project::orderBy('id', 'ASC')->get();

        $expect = [];

        foreach ($projects as $project) {
            $expect['data']['projects']['data'][] = [
                'id' => (string) $project->id,
                'name' => $project->name,
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                query {
                    projects{
                        data {
                            id
                            name
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson($expect);
    }

    public function testQueryProjectsWithFilterById(): void
    {
        $projects = Project::where('id', 1)->orderBy('id', 'ASC')->get();

        $expect = [];

        foreach ($projects as $project) {
            $expect['data']['projects']['data'][] = [
                'id' => (string) $project->id,
                'name' => (string) $project['name'],
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                query Projects($id: Mixed) {
                    projects (
                        filter: { column: ID, operator: EQ, value: $id }
                    ) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson($expect);
    }

    public function testQueryProjectsWithFilterByModuleId(): void
    {
        $projects = Project::where('module_id', 1)->orderBy('id', 'ASC')->get();

        $expect = [];

        foreach ($projects as $project) {
            $expect['data']['projects']['data'][] = [
                'id' => (string) $project->id,
                'name' => (string) $project['name'],
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                query Projects($module_id: Mixed) {
                    projects (
                        filter: { column: MODULE_ID, operator: EQ, value: $module_id }
                    ) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'module_id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson($expect);
    }

    public function testQueryProjectsWithFilterByCompanyId(): void
    {
        $projects = Project::where('company_id', 1)->orderBy('id', 'ASC')->get();

        $expect = [];

        foreach ($projects as $project) {
            $expect['data']['projects']['data'][] = [
                'id' => (string) $project->id,
                'name' => (string) $project['name'],
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                query Projects($company_id: Mixed) {
                    projects (
                        filter: { column: COMPANY_ID, operator: EQ, value: $company_id }
                    ) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'company_id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson($expect);
    }
}
