<?php

namespace Unit;

use App\Enums\PaymentUrgencyEnum;
use App\Models\PriceListFeeCurrency;
use App\Services\CommissionService;
use Tests\TestCase;

class PaymentsTest extends TestCase
{

    /**
     * @dataProvider provideTestGetFee
     *
     * @return void
     */
    public function testGetFee($amount, $excepted): void
    {
        $collection = collect([
            [
                [
                    'fee' => 10,
                    'mode' => 'Fix',
                ],
                [
                    'mode' => 'Range',
                    'amount_from' => 100,
                    'amount_to' => 300,
                ],
            ],


            [
                [
                    'fee' => 15,
                    'mode' => 'Fix',
                ],
                [
                    'mode' => 'Range',
                    'amount_from' => 900,
                    'amount_to' => 1200,
                ],
                [
                    'mode' => 'Percent',
                    'percent' => 10,
                ],
            ],

            [
                [
                    'mode' => 'Range',
                    'amount_from' => 100,
                    'amount_to' => 500,
                ],
                [
                    'mode' => 'Percent',
                    'percent' => 20,
                ],
            ],

            [
                [
                    'mode' => 'Percent',
                    'percent' => 2,
                ],
            ],


            [
                [
                    'mode' => 'Fix',
                    'fee' => 40,
                ],
            ],

        ]);

//        foreach ($collection as $value){
//            $fee = (new CommissionService())->getFee(collect($value), $amount, PaymentUrgencyEnum::STANDART->value);
//            $this->assertEqualsWithDelta($excepted, $fee, 0.00001);
//        }


    }

    public function provideTestGetFee(): array
    {
        return [
            [1000, 175.0],
            [500, 150.0],
            [10000, 240.0],
            [100, 72.0],
            [10, 40.2],
        ];
    }
}
