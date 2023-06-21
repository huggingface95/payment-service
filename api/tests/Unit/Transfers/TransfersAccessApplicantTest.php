<?php

namespace Unit\Transfers;

use App\Enums\ApplicantTypeEnum;
use App\Exceptions\GraphqlException;
use App\Models\ApplicantIndividual;
use App\Models\TransferOutgoing;
use App\Observers\Traits\AccessTransfersTrait;
use Mockery;
use PHPUnit\Framework\TestCase;

class TransfersAccessApplicantTest extends TestCase
{

    use AccessTransfersTrait {
        checkApplicantAccess as public;
    }

    /**
     * @dataProvider provideTestCheckApplicantAccessDisallow
     */
    public function testCheckApplicantAccessDisallow($applicantId, $modelData, $msg = '')
    {
        $this->expectException(GraphqlException::class);
        $this->expectExceptionMessage($msg);

        $model = Mockery::mock(TransferOutgoing::class);
        $model->shouldReceive('getAttribute')
            ->with('account')
            ->andReturn($account = Mockery::mock());
        $account->owner_id = $modelData['owner_id'];
        $account->client_id = $modelData['client_id'];
        $account->client_type = 'ApplicantIndividual';

        $model->shouldReceive('getAttribute')
            ->with('requested_by_id')
            ->andReturn($modelData['requested_by_id']);

        $applicant = Mockery::mock(ApplicantIndividual::class);

        $applicant->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn($applicantId);

        $this->checkApplicantAccess($model, $applicant);
    }

    /**
     * @dataProvider provideTestCheckApplicantAccessDisallow
     *
     * @return void
     */
    public function provideTestCheckApplicantAccessDisallow(): array
    {
        return [
            // Applicant cant work with not related accounts
            [
                24,
                [
                    'owner_id' => 3,
                    'client_id' => 12,
                    'client_type' => ApplicantTypeEnum::INDIVIDUAL->toString(),
                    'requested_by_id' => 24,
                ],
                'The account must belong to the applicant',
            ],
            [
                24,
                [
                    'owner_id' => 3,
                    'client_id' => 12,
                    'client_type' => ApplicantTypeEnum::COMPANY->toString(),
                    'requested_by_id' => 24,
                ],
                'The account must belong to the applicant',
            ],
            [
                24,
                [
                    'owner_id' => 3,
                    'client_id' => 3,
                    'client_type' => ApplicantTypeEnum::COMPANY->toString(),
                    'requested_by_id' => 3,
                ],
                'The account must belong to the applicant',
            ],
        ];
    }

    /**
     * @dataProvider provideTestCheckApplicantAccessAllow
     */
    public function testCheckApplicantAccessAllow($applicantId, $modelData, $msg = '')
    {
        $model = Mockery::mock(TransferOutgoing::class);
        $model->shouldReceive('getAttribute')
            ->with('account')
            ->andReturn($account = Mockery::mock());
        $account->owner_id = $modelData['owner_id'];
        $account->client_id = $modelData['client_id'];
        $account->client_type = 'ApplicantIndividual';

        $model->shouldReceive('getAttribute')
            ->with('requested_by_id')
            ->andReturn($modelData['requested_by_id']);

        $applicant = Mockery::mock(ApplicantIndividual::class);

        $applicant->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn($applicantId);

        try {
            $this->checkApplicantAccess($model, $applicant);

            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail('Unexpected exception occurred: ' . $e->getMessage());
        }
    }

    /**
     * @dataProvider provideTestCheckApplicantAccessAllow
     *
     * @return void
     */
    public function provideTestCheckApplicantAccessAllow(): array
    {
        return [
            // Applicant can work with transfers which was created by Applicant
            [
                24,
                [
                    'owner_id' => 24,
                    'client_id' => 12,
                    'client_type' => ApplicantTypeEnum::INDIVIDUAL->toString(),
                    'requested_by_id' => 24,
                ],
            ],
            [
                24,
                [
                    'owner_id' => 3,
                    'client_id' => 24,
                    'client_type' => ApplicantTypeEnum::INDIVIDUAL->toString(),
                    'requested_by_id' => 24,
                ],
            ],
            // Applicant can work with transfers which was created by Member
            [
                24,
                [
                    'owner_id' => 24,
                    'client_id' => 12,
                    'client_type' => ApplicantTypeEnum::COMPANY->toString(),
                    'requested_by_id' => 2,
                ],
            ],
            [
                24,
                [
                    'owner_id' => 1,
                    'client_id' => 24,
                    'client_type' => ApplicantTypeEnum::COMPANY->toString(),
                    'requested_by_id' => 2,
                ],
            ],
        ];
    }
    
}
