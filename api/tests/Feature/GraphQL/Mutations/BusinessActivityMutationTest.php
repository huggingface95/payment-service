<?php

namespace Feature\GraphQL\Mutations;

use App\Models\BusinessActivity;
use App\Models\PaymentBank;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BusinessActivityMutationTest extends TestCase
{
    /**
     * Regions Mutation Testing
     *
     * @return void
     */
    public function testCreateBusinessActivityNoAuth(): void
    {
        $seq = DB::table('business_activity')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE business_activity_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateBusinessActivity(
                $name: String!,
            ) {
                createBusinessActivity(
                    name: $name,
                ) {
                    id
                    name
                }
            }
        ', [
            'name' => 'Test business',
        ])->seeJsonContains([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateBusinessActivity(): void
    {
        $seq = DB::table('business_activity')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE business_activity_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(['query' => 'mutation CreateBusinessActivity(
                $name: String!,
            ) {
                createBusinessActivity(
                    name: $name,
                ) {
                    id
                    name
                }
            }',
            'variables' => [
                'name' => 'Test business activity',
            ],
        ],
        [
            'Authorization' => 'Bearer ' . $this->login(),
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['createBusinessActivity']['id'],
                'name' => $id['data']['createBusinessActivity']['name'],
            ],
        ]);
    }

    public function testUpdateBusinessActivity(): void
    {
        $businessActivity = BusinessActivity::orderBy('id', 'DESC')
            ->first();

        $this->postGraphQL(['query' => 'mutation UpdateBusinessActivity(
                $id: ID!,
                $name: String!,
            ) {
                updateBusinessActivity(
                    id: $id,
                    name: $name,
                ) {
                    id
                    name
                }
            }',
            'variables' => [
                'id' => $businessActivity->id,
                'name' => 'New Activity',
            ],
        ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['updateBusinessActivity']['id'],
                'name' => $id['data']['updateBusinessActivity']['name'],
            ],
        ]);
    }
}
