<?php

namespace Feature\GraphQL\Mutations;

use Tests\TestCase;

class ModulesMutationTest extends TestCase
{
    /**
     * Modules Mutation Testing
     *
     * @return void
     */
    public function testCreateModuleNoAuth(): void
    {
        $this->graphQL('
            mutation CreateModule(
                $name: String!,
            ) {
                createModule(
                    name: $name,
                ) {
                    id
                }
            }
        ', [
            'name' => 'Test module',
        ])->seeJsonContains([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateModule(): void
    {
        $this->postGraphQL(['query' => '
            mutation CreateModule(
                $name: String!,
            ) {
                createModule(
                    name: $name,
                ) {
                    id
                    name
                }
            }
        ', 'variables' => [
            'name' => 'Test module',
        ]
        ],
        [
            'Authorization' => 'Bearer ' . $this->login(),
        ])->seeJsonContains([
            'message' => 'Access denied',
        ]);
    }
}
