<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\FeeMode;
use Tests\TestCase;

class FeeModesQueryTest extends TestCase
{
    public function testQueryFeeModesNoAuth(): void
    {
        $this->graphQL('
            {
                feeModes {
                    id
                    name
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryFeeModesList(): void
    {
        $feeModes = FeeMode::get();

        $expect = [
            'data' => [
                'feeModes' => [],
            ],
        ];

        foreach ($feeModes as $feeMode) {
            $expect['data']['feeModes'][] = [
                'id' => (string) $feeMode['id'],
                'name' => (string) $feeMode['name'],
            ];
        }

        $this->PostGraphQL([
            'query' => '
                {
                    feeModes {
                        id
                        name
                    }
                }',
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJson($expect);
    }
}
