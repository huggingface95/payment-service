<?php

namespace App\Rules;

use App\Models\Files;
use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Support\Str;

class FileOwner implements ImplicitRule
{
    public function __construct(private string $entity_type)
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (! empty($value)) {
            $file = Files::where('id', $value)
                ->where('entity_type', $this->entity_type)
                ->first();

            if (! $file) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The photo is not associated with the '.Str::ucfirst($this->entity_type).'.';
    }
}
