<?php

namespace Feature\GraphQL\Queries;

use App\Models\ApplicantCompanyBusinessType;
use Tests\TestCase;

class ApplicantCompanyBusinessTypesQueryTest extends TestCase
{
    public function testQueryApplicantCompanyBusinessTypesNoAuth(): void
    {
        $this->graphQL('
            {
                applicantCompanyBusinessTypes {
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

    public function testQueryApplicantCompanyBusinessType(): void
    {
        $businessType = ApplicantCompanyBusinessType::orderBy('id', 'ASC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query ApplicantCompanyBusinessType($id: ID) {
                    applicantCompanyBusinessType(id: $id) {
                        id
                        name
                    }
                }',
                'variables' => [
                    'id' => $businessType->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'applicantCompanyBusinessType' => [
                    'id' => (string) $businessType->id,
                    'name' => (string) $businessType->name,
                ],
            ],
        ]);
    }

    public function testQueryApplicantCompanyBusinessTypesList(): void
    {
        $businessTypes = ApplicantCompanyBusinessType::orderBy('id', 'ASC')->get()->paginate(10);

        foreach ($businessTypes as $businessType) {
            $data[] = [
                'id' => (string) $businessType->id,
                'name' => (string) $businessType->name,
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                {
                    applicantCompanyBusinessTypes (orderBy: { column: ID, order: ASC }) {
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
                'applicantCompanyBusinessTypes' => [
                    'data' => $data,
                ],
            ],
        ]);
    }

    public function testQueryApplicantCompanyBusinessTypesWithFilterByName(): void
    {
        $businessType = ApplicantCompanyBusinessType::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $businessType->id,
            'name' => (string) $businessType->name,
        ];

        $this->postGraphQL(
            [
                'query' => 'query ApplicantCompanyBusinessTypes($name: Mixed) {
                    applicantCompanyBusinessTypes (
                        filter: { column: NAME, operator: ILIKE, value: $name }
                    ) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'name' => (string) $businessType->name,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }
}
