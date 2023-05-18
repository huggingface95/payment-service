<?php

namespace Feature\GraphQL\Queries;

use App\Models\BusinessActivity;
use Tests\TestCase;

class BusinessActivityQueryTest extends TestCase
{
    public function testQueryBusinessActivitiesNoAuth(): void
    {
        $this->graphQL('
            {
                businessActivities {
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

    public function testQueryBusinessActivity(): void
    {
        $businessActivity = BusinessActivity::orderBy('id', 'ASC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query BusinessActivity($id: ID) {
                    businessActivity(id: $id) {
                            id
                            name
                    }
                }',
                'variables' => [
                    'id' => $businessActivity->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'businessActivity' => [
                    'id' => (string) $businessActivity->id,
                    'name' => (string) $businessActivity->name,
                ],
            ],
        ]);
    }

    public function testQueryBusinessActivitiesList(): void
    {
        $businessActivities = BusinessActivity::get();

        foreach ($businessActivities as $businessActivity) {
            $data[] = [
                'id' => (string) $businessActivity->id,
                'name' => (string) $businessActivity->name,
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                {
                    businessActivities {
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
        )->seeJson([
            'data' => [
                'businessActivities' => [
                    'data' => $data,
                ],
            ],
        ]);
    }

    public function testQueryBusinessActivitiesWithFilterByName(): void
    {
        $businessActivities = BusinessActivity::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $businessActivities->id,
            'name' => (string) $businessActivities->name,
        ];

        $this->postGraphQL(
            [
                'query' => 'query BusinessActivities($name: Mixed) {
                    businessActivities (
                        filter: { column: NAME, operator: ILIKE, value: $name }
                    ) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'name' => (string) $businessActivities->name,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryBusinessActivitiesWithFilterById(): void
    {
        $businessActivities = BusinessActivity::orderBy('id', 'ASC')
            ->first();

        $data['data']['businessActivities']['data'][] = [
            'id' => (string) $businessActivities->id,
            'name' => (string) $businessActivities->name,
        ];

        $this->postGraphQL(
            [
                'query' => 'query BusinessActivities($id: Mixed) {
                    businessActivities (
                        filter: { column: ID,  value: $id }
                    ) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => $businessActivities->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryBusinessActivitiesWithFilterByBusinessActivityId(): void
    {
        $businessActivities = BusinessActivity::orderBy('id', 'ASC')
            ->first();

        $commissionTemplate = $businessActivities->commissionTemplate()
            ->first();

        $data = [
            'id' => (string) $businessActivities->id,
            'name' => (string) $businessActivities->name,
        ];

        $this->postGraphQL(
            [
                'query' => 'query BusinessActivities($id: Mixed) {
                    businessActivities (
                        filter: { column: HAS_COMMISSION_TEMPLATE_FILTER_BY_ID,  value: $id }
                    ) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => $commissionTemplate->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }
}
