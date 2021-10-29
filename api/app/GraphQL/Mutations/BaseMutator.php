<?php

namespace App\GraphQL\Mutations;


use GraphQL\Exception\InvalidArgument;

class BaseMutator
{

    /**
     * @param array $additionalFields
     * @return array
     */
    public function setAdditionalField(array $additionalFields):array
    {
        $fields = [];
            foreach ($additionalFields as $additionalField) {
                if (strlen($additionalField['field_value']) > config('app.max_length_string')) {
                    throw new InvalidArgument("Max length field is " . config('app.max_length_string'));
                }
                if ($additionalField['field_type'] === "Text") {
                    $additionalField['field_value'] = filter_var($additionalField['field_value'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
                }
                if ($additionalField['field_type'] === "TextArea") {
                    $additionalField['field_value'] = filter_var($additionalField['field_value'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                }
                $fields[] = $additionalField;
            }

        return $fields;
    }
}
