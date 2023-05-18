<?php

namespace Feature\GraphQL\Mutations;

use App\Models\ApplicantCompanyBusinessType;
use Tests\TestCase;

class ApplicantCompanyBusinessTypesMutationTest extends TestCase
{
    /**
     * ApplicantCompanyBusinessTypes Mutation Testing
     *
     * @return void
     */
    public function testCreateApplicantCompanyBusinessTypeNoAuth(): void
    {
        $this->graphQL('
            mutation CreateApplicantCompanyBusinessType(
                    $name: String!
                ) {
                createApplicantCompanyBusinessType(
                    name: $name
                )
              {
                id
                name
              }
           }
        ', [
            'name' => 'Test BusinessType',
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateApplicantCompanyBusinessType(): void
    {
        $this->postGraphQL(
            [
                'query' => '
                    mutation CreateApplicantCompanyBusinessType(
                        $name: String!
                    ) {
                    createApplicantCompanyBusinessType(
                        name: $name
                    )
                  {
                    id
                    name
                  }
                }',
                'variables' => [
                    'name' => 'Test BusinessType',
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['createApplicantCompanyBusinessType']['id'],
                'name' => $id['data']['createApplicantCompanyBusinessType']['name'],
            ],
        ]);
    }

    public function testUpdateApplicantCompanyBusinessType(): void
    {
        $businessType = ApplicantCompanyBusinessType::orderBy('id', 'DESC')->first();

        $this->postGraphQL(
            [
                'query' => '
                    mutation UpdateApplicantCompanyBusinessType(
                        $id: ID!
                        $name: String!
                    ) {
                    updateApplicantCompanyBusinessType(
                        id: $id
                        name: $name
                    )
                  {
                    id
                    name
                  }
                }',
                'variables' => [
                    'id' => $businessType->id,
                    'name' => $businessType->name,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['updateApplicantCompanyBusinessType']['id'],
                'name' => $id['data']['updateApplicantCompanyBusinessType']['name'],
            ],
        ]);
    }
}
