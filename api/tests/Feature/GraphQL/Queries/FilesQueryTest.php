<?php

namespace Feature\GraphQL\Queries;

use App\Models\Files;
use Tests\TestCase;

class FilesQueryTest extends TestCase
{
    public function testQueryFilesNoAuth(): void
    {
        $this->graphQL('
            {
                files {
                    data {
                      id
                      file_name
                      mime_type
                      size
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryFile(): void
    {
        $file = Files::orderBy('id', 'ASC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query File($id: ID) {
                    file(id: $id) {
                          id
                          file_name
                          mime_type
                          size
                    }
                }',
                'variables' => [
                    'id' => $file->id,
                ]
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'file' => [
                        'id' => (string) $file->id,
                        'file_name' => (string) $file->file_name,
                        'mime_type' => (string) $file->mime_type,
                        'size' => $file->size,
                    ],
                ],
        ]);
    }

    public function testQueryFilesList(): void
    {
        $files = Files::get();

        foreach ($files as $file) {
            $data[] = [
                'id' => (string) $file->id,
                'file_name' => (string) $file->file_name,
                'mime_type' => (string) $file->mime_type,
                'size' => $file->size,
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                {
                    files {
                        data {
                            id
                            file_name
                            mime_type
                            size
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'files' => [
                    'data' => $data,
                ],
            ],
        ]);
    }

    public function testQueryFilesWithFilterByEntityType(): void
    {
        $files = Files::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $files->id,
            'file_name' => (string) $files->file_name,
            'mime_type' => (string) $files->mime_type,
            'size' => $files->size,
        ];

        $this->postGraphQL(
            [
                'query' => 'query Files($type: Mixed) {
                    files (
                        filter: { column: ENTITY_TYPE, operator: ILIKE, value: $type }
                    ) {
                        data {
                            id
                            file_name
                            mime_type
                            size
                        }
                    }
                }',
                'variables' => [
                    'type' => (string) $files->entity_type,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryFilesWithFilterByAuthorId(): void
    {
        $files = Files::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $files->id,
            'file_name' => (string) $files->file_name,
            'mime_type' => (string) $files->mime_type,
            'size' => $files->size,
        ];

        $this->postGraphQL(
            [
                'query' => 'query Files($id: Mixed) {
                    files (
                        filter: { column: AUTHOR_ID, value: $id }
                    ) {
                        data {
                            id
                            file_name
                            mime_type
                            size
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $files->author_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains($data);
    }
}
