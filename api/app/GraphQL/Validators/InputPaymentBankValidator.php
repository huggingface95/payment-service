<?php

namespace App\GraphQL\Validators;

use App\DTO\TransformerDTO;
use Nuwave\Lighthouse\Validation\Validator;

final class InputPaymentBankValidator extends Validator
{


    public function rules(): array
    {
        return (array) TransformerDTO::transform(\App\DTO\GraphQLValidator\InputPaymentBankValidator::class, $this->args->toArray());
    }
}
