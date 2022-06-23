<?php

namespace App\DTO;

class TransformerDTO
{
    /**
     * @template Tr
     *
     * @param class-string<Tr> $className
     * @param ...$args
     *
     * @return Tr
     */
    public static function transform(string $className, ...$args)
    {
        return $className::transform(...$args);
    }
}
