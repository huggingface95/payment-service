<?php

namespace App\Exceptions;

use Exception;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;

class GraphqlException extends Exception implements RendersErrorsExtensions
{
    /**
     * @var @string
     */
    protected $category;

    protected $code;

    public function __construct(string $message, string $category = 'internal', $code = 500)
    {
        parent::__construct($message);
        $this->category = $category;
        $this->code = $code;
    }

    /**
     * Returns true when exception message is safe to be displayed to a client.
     *
     * @api
     * @return bool
     */
    public function isClientSafe(): bool
    {
        return true;
    }

    /**
     * Returns string describing a category of the error.
     *
     * Value "graphql" is reserved for errors produced by query parsing or validation, do not use it.
     *
     * @api
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * Return the content that is put in the "extensions" part
     * of the returned error.
     *
     * @return array
     */
    public function extensionsContent(): array
    {
        return [
            'code' => $this->getCode(),
            'systemMessage'=> $this->getMessage(),
        ];
    }
}
