<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use GraphQL\Exception\InvalidArgument;

class BaseMutator
{
    /**
     * @param  array  $additionalFields
     * @return array
     */
    public function setAdditionalField(array $additionalFields): array
    {
        $fields = [];
        foreach ($additionalFields as $additionalField) {
            if (strlen($additionalField['field_value']) > config('app.max_length_string')) {
                throw new InvalidArgument('Max length field is '.config('app.max_length_string'));
            }
            if ($additionalField['field_type'] === 'Text') {
                $additionalField['field_value'] = filter_var($additionalField['field_value'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
            }
            if ($additionalField['field_type'] === 'TextArea') {
                $additionalField['field_value'] = filter_var($additionalField['field_value'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            $fields[] = $additionalField;
        }

        return $fields;
    }

    protected function validEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @throws GraphqlException
     */
    protected function validIp(string $ipAddress): array
    {
        $ip_address = str_replace(' ', '', explode(',', $ipAddress));
        for ($i = 0; $i < count($ip_address); $i++) {
            if (!filter_var($ip_address[$i], FILTER_VALIDATE_IP)) {
                throw new GraphqlException('Not a valid ip address. Address format xxx.xxx.xxx.xxx and must be comma separated', 'internal', 403);
            }
        }

        return $ip_address;
    }
}
