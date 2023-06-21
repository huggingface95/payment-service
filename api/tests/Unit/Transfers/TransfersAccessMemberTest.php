<?php

namespace Unit\Transfers;

use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\Models\ApplicantIndividual;
use App\Models\Members;
use App\Models\TransferOutgoing;
use App\Observers\Traits\AccessTransfersTrait;
use Mockery;
use PHPUnit\Framework\TestCase;

class TransfersAccessMemberTest extends TestCase
{

    use AccessTransfersTrait {
        checkApplicantAccess as public;
    }

    /**
     * @dataProvider provideTestCheckMemberAccessDisallow
     */
    public function testCheckMemberAccessDisallow($memberData, $modelData, $originalModelData = [], $msg = '')
    {
        $originalModelData = array_merge($modelData, $originalModelData);

        $this->expectException(GraphqlException::class);
        $this->expectExceptionMessage($msg);
    
        $model = Mockery::mock(TransferOutgoing::class);
    
        $model->shouldReceive('getAttribute')
            ->with('requested_by_id')
            ->andReturn($modelData['requested_by_id']);
        
        $model->shouldReceive('getAttribute')
            ->with('user_type')
            ->andReturn($modelData['user_type']);

        $model->shouldReceive('getAttribute')
            ->with('company_id')
            ->andReturn($modelData['company_id']);

        $model->shouldReceive('getAttribute')
            ->with('status_id')
            ->andReturn($modelData['status_id']);
    
        $model->shouldReceive('getOriginal')
            ->andReturn($originalModelData);
    
        $member = Mockery::mock(Members::class);
    
        $member->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn($memberData['id']);
    
        $member->shouldReceive('getAttribute')
            ->with('company_id')
            ->andReturn($memberData['company_id']);
    
        $this->checkMemberAccess($model, $member);
    }
    
    /**
     * @dataProvider provideTestCheckMemberAccessDisallow
     *
     * @return void
     */
    public function provideTestCheckMemberAccessDisallow(): array
    {
        return [
            // Member can't work with transfers from another Members (even from the same company)
            [
                [
                    'id' => 2,
                    'company_id' => 1,
                ],
                [
                    'requested_by_id' => 20,
                    'user_type' => class_basename(Members::class),
                    'company_id' => 11,
                    'status_id' => PaymentStatusEnum::UNSIGNED->value,
                ],
                [
                    'status_id' => PaymentStatusEnum::CANCELED->value,
                ],
                'The transfer must belong to the member or applicant from the same member company',
            ],
            [
                [
                    'id' => 2,
                    'company_id' => 11,
                ],
                [
                    'requested_by_id' => 20,
                    'user_type' => class_basename(Members::class),
                    'company_id' => 11,
                    'status_id' => PaymentStatusEnum::UNSIGNED->value,
                ],
                [
                    'status_id' => PaymentStatusEnum::CANCELED->value,
                ],
                'The transfer must belong to the member or applicant from the same member company',
            ],
            [
                [
                    'id' => 2,
                    'company_id' => 11,
                ],
                [
                    'requested_by_id' => 20,
                    'user_type' => class_basename(ApplicantIndividual::class),
                    'company_id' => 11,
                    'status_id' => PaymentStatusEnum::UNSIGNED->value,
                ],
                [
                    'status_id' => PaymentStatusEnum::UNSIGNED->value,
                ],
                'Member cannot Edit applicant\'s transfers',
            ],
            // Member can\'t Cancel Applicant\'s transfers in Status not equal Unsigned
            [
                [
                    'id' => 2,
                    'company_id' => 11,
                ],
                [
                    'requested_by_id' => 24,
                    'user_type' => class_basename(ApplicantIndividual::class),
                    'company_id' => 11,
                    'status_id' => PaymentStatusEnum::PENDING->value,
                ],
                [
                    'status_id' => PaymentStatusEnum::CANCELED->value,
                ],
                'Transfer must be in the Unsigned status',
            ],
        ];
    }

     /**
     * @dataProvider provideTestCheckMemberAccessAllow
     */
    public function testCheckMemberAccessAllow($memberData, $modelData, $originalModelData = [], $msg = '')
    {
        $originalModelData = array_merge($modelData, $originalModelData);
        $model = Mockery::mock(TransferOutgoing::class);

        $model->shouldReceive('getAttribute')
            ->with('requested_by_id')
            ->andReturn($modelData['requested_by_id']);
        
        $model->shouldReceive('getAttribute')
            ->with('user_type')
            ->andReturn($modelData['user_type']);

        $model->shouldReceive('getAttribute')
            ->with('company_id')
            ->andReturn($modelData['company_id']);
        
        $model->shouldReceive('getAttribute')
            ->with('status_id')
            ->andReturn($modelData['status_id']);
    
        $model->shouldReceive('getOriginal')
            ->andReturn($originalModelData);
    
        $member = Mockery::mock(Members::class);
    
        $member->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn($memberData['id']);
    
        $member->shouldReceive('getAttribute')
            ->with('company_id')
            ->andReturn($memberData['company_id']);
    
        try {
            $this->checkMemberAccess($model, $member);

            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail('Unexpected exception occurred: ' . $e->getMessage());
        }
    }
    

    /**
     * @dataProvider provideTestCheckMemberAccessAllow
     *
     * @return void
     */
    public function provideTestCheckMemberAccessAllow(): array
    {
        return [
            // Member can work with own transfers
            [
                [
                    'id' => 2,
                    'company_id' => 1,
                ],
                [
                    'requested_by_id' => 2,
                    'user_type' => class_basename(Members::class),
                    'company_id' => 11,
                    'status_id' => PaymentStatusEnum::CANCELED->value,
                ],
                [
                    'status_id' => PaymentStatusEnum::UNSIGNED->value,
                ],
            ],
            // Member can Cancel Applicant\'s transfers
            [
                [
                    'id' => 2,
                    'company_id' => 11,
                ],
                [
                    'requested_by_id' => 24,
                    'user_type' => class_basename(ApplicantIndividual::class),
                    'company_id' => 11,
                    'status_id' => PaymentStatusEnum::CANCELED->value,
                ],
                [
                    'status_id' => PaymentStatusEnum::UNSIGNED->value,
                ],
            ],
        ];
    }

}
