<?php

namespace Feature\GraphQL\Mutations;

use App\Models\Files;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FilesMutationTest extends TestCase
{
    /**
     * Files Mutation Testing
     *
     * @return void
     */
    public function testDeleteFilesNoAuth(): void
    {
        $files = Files::orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeleteFile($id: ID!) {
                    deleteFile(id: $id) {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $files[0]->id,
                ],
            ]
        );

        $this->seeJsonContains([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testDeleteFiles(): void
    {
        $files = Files::orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeleteFile($id: ID!) {
                    deleteFile(id: $id) {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $files[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['deleteFile']['id'],
            ],
        ]);
    }
}
