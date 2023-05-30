<?php

namespace Feature\GraphQL\Queries;

use App\Models\Module;
use Tests\TestCase;

class ModulesQueryTest extends TestCase
{
    public function testQueryModulesNoAuth(): void
    {
        $this->graphQL('
            {
                modules {
                    id
                    name
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryModule(): void
    {
        $modules = Module::orderBy('id', 'ASC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query Module($id: ID) {
                    module(id: $id) {
                            id
                            name
                    }
                }',
                'variables' => [
                    'id' => $modules->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'module' => [
                    'id' => (string) $modules->id,
                    'name' => (string) $modules->name,
                ],
            ],
        ]);
    }

    public function testQueryModulesNoKyc(): void
    {
        $modules = Module::where('name', '<>', 'KYC')->get();

        foreach ($modules as $module) {
            $data = [
                'id' => (string) $module->id,
                'name' => (string) $module->name,
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                {
                    modules {
                        id
                        name
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'data' => [
                'modules' => [
                    $data,
                ],
            ],
        ]);
    }

    public function testQueryModulesNoKycFilterByName(): void
    {
        $modules = Module::where('name', '<>', 'KYC')->get();

        foreach ($modules as $module) {
            $data = [
                'id' => (string) $module->id,
                'name' => (string) $module->name,
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                query Modules ($name: Mixed){
                    modules (where:{
                        column: NAME
                        value: $name
                    }) {
                        id
                        name
                    }
                }',
                'variables' => [
                    'name' => $modules[0]->name,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'data' => [
                'modules' => [
                    $data,
                ],
            ],
        ]);
    }

    public function testQueryModulesWithKyc(): void
    {
        $modules = Module::where('name', '<>', 'KYC')->get();

        $data = [
            'id' => (string) $modules[0]->id,
            'name' => (string) $modules[0]->name,
        ];

        $this->postGraphQL(
            [
                'query' => '
                query ModulesWithKyc ($name: Mixed){
                    modulesWithKyc (where:{
                        column: NAME
                        value: $name
                    }) {
                        id
                        name
                    }
                }',
                'variables' => [
                    'name' => $modules[0]->name,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            $data,
        ]);
    }

    public function testQueryModulesWithKycFilterByName(): void
    {
        $modules = Module::where('name', '<>', 'KYC')->get();

        $data = [
            'id' => (string) $modules[0]->id,
            'name' => (string) $modules[0]->name,
        ];

        $this->postGraphQL(
            [
                'query' => '
                {
                    modulesWithKyc {
                        id
                        name
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            $data,
        ]);
    }
}
