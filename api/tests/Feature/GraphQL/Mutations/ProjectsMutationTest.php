<?php

namespace Tests\Feature\GraphQL\Mutations;

use App\Models\Project;
use Tests\TestCase;

class ProjectsMutationTest extends TestCase
{
    /**
     * Projects Mutation Testing
     *
     * @return void
     */

    public function testCreateProject(): void
    {
        $this->login();

        $this->graphQL('
            mutation CreateProject(
                $name: String!
                $url: String
                $description: String
                $support_email: String
                $login_url: String
                $sms_sender_name: String
                $client_url: String
                $company_id: ID!
            ) {
                createProject(
                    input: {
                        name: $name
                        url: $url
                        description: $description
                        support_email: $support_email
                        login_url: $login_url
                        sms_sender_name: $sms_sender_name
                        client_url: $client_url
                        company_id: $company_id
                    }
                )
                {
                    id
                }
           }
        ', [
            'name' => 'Test Co',
            'url' => 'https://test.co',
            'description' => 'Description of company',
            'support_email' => 'test@test.co',
            'login_url' => 'https://client.test.co/login',
            'sms_sender_name' => 'SMS Testco',
            'client_url' => 'https://client.test.co',
            'company_id' => 1,
        ]);

        $response = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            'data' => [
                'createProject' => [
                    'id' => $response['data']['createProject']['id'],
                ]
            ],
        ]);
    }

    public function testUpdateProject(): void
    {
        $this->login();

        $project = Project::latest()->first();

        $this->graphQL('
            mutation UpdateProject(
                $id: ID!
                $name: String!
                $url: String
                $description: String
                $support_email: String
                $company_id: ID!
            )
            {
                updateProject(
                    id: $id
                    input: {
                        name: $name
                        url: $url
                        description: $description
                        support_email: $support_email
                        company_id: $company_id
                    }
                )
                {
                    id
                    name
                    url
                    description
                    support_email
                }
            }
        ', [
            'id' => (string) $project->id,
            'name' => 'New Test co',
            'url' => 'https://new-test.co',
            'description' => 'Updated description',
            'support_email' => 'updt@test.co',
            'company_id' => 2,
        ]);

        $response = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateProject' => [
                    'id' => $response['data']['updateProject']['id'],
                    'name' => $response['data']['updateProject']['name'],
                    'url' => $response['data']['updateProject']['url'],
                    'description' => $response['data']['updateProject']['description'],
                    'support_email' => $response['data']['updateProject']['support_email'],
                ],
            ],
        ]);
    }
}
