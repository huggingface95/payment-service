<?php

namespace App\DTO\Requisite;

class RequisiteSendEmailDTO
{
    public string $content;

    public static function transform(array $args): self
    {
        $dto = new self();
        $dto->content = '
                <table>
                  <tr>
                    <th colspan="2">Transfer Details ('.$args['currency'].')</th>
                  </tr>
                  <tr>
                    <td>Beneficiary\'s Name:</td>
                    <td>'.$args['beneficiary'].'</td>
                  </tr>
                  <tr>
                    <td>Beneficiary\'s Address:</td>
                    <td>'.$args['address'].'</td>
                  </tr>
                   <tr>
                    <td>Country:</td>
                    <td>'.$args['country'].'</td>
                  </tr>
                   <tr>
                    <td>Bank name:</td>
                    <td>'.$args['bank_name'].'</td>
                  </tr>
                   <tr>
                    <td>IBAN / Account #:</td>
                    <td>'.$args['iban'].'</td>
                  </tr>
                   <tr>
                    <td>SWIFT:</td>
                    <td>'.$args['swift_code'].'</td>
                  </tr>
                   <tr>
                    <td>Bank Address:</td>
                    <td>'.$args['bank_address'].'</td>
                  </tr>
                  <tr>
                    <td>Bank country:</td>
                    <td>'.$args['bank_country'].'</td>
                  </tr>
                </table>
                ';

        return $dto;
    }
}
