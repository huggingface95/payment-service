<?php

namespace Tests\Unit;

use App\Services\PaymentsService;
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
            (object) [
                'currency_id' => 1,
                'fee' => collect((object) [
                    [
                        'fee' => 10,
                        'mode' => 'Fix',
                    ],
                    [
                        'mode' => 'Range',
                        'amount_from' => 100,
                        'amount_to' => 300,
                    ],
                ]),
            ],
            (object) [
                'currency_id' => 1,
                'fee' => collect((object) [
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
                ]),
            ],
            (object) [
                'currency_id' => 1,
                'fee' => collect((object) [
                    [
                        'mode' => 'Range',
                        'amount_from' => 100,
                        'amount_to' => 500,
                    ],
                    [
                        'mode' => 'Percent',
                        'percent' => 20,
                    ],
                ]),
            ],
            (object) [
                'currency_id' => 1,
                'fee' => collect((object) [
                    [
                        'mode' => 'Percent',
                        'percent' => 2,
                    ],
                ]),
            ],
            (object) [
                'currency_id' => 1,
                'fee' => collect((object) [
                    [
                        'mode' => 'Fix',
                        'fee' => 40,
                    ],
                ]),
            ],
        ]);

        $fee = (new PaymentsService())->getFee($collection, $amount, 1);

        $this->assertEqualsWithDelta($excepted, $fee, 0.00001);
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
