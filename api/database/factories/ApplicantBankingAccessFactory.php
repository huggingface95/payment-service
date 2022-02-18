<?php

namespace Database\Factories;

use App\Models\ApplicantBankingAccess;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicantBankingAccessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ApplicantBankingAccess::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'applicant_individual_id' => 2,
            'applicant_company_id' => 2,
            'member_id' => 2,
            'can_create_payment' => true,
            'can_sign_payment' => true,
            'contact_administrator' => true,
            'daily_limit' => 5000.00,
            'monthly_limit' => 50000.00,
            'operation_limit' => 1000.00
        ];
    }
}
