<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class RepositoryException
 */
class QueryException extends Exception
{
    public array $codes = [
        '23503' => [
            [
                'code' => 401,
                'message' => 'This {table} already in use',
                'regexp' => "(?:SQLSTATE\[23503\].*?\")(.*?)(?:\".*)",
            ]
        ],
        '23505' => [
            [
                'code' => 400,
                'message' => 'Failed to add the same pair of region and currency',
                'regexp' => "(?:SQLSTATE\[23505\].*?\")(.*?)(bank_correspondent_currencies_regions_bank)",
            ]
        ],
    ];

    public function __construct($message = '', $code = Response::HTTP_INTERNAL_SERVER_ERROR, Throwable $previous = null)
    {
        list($message, $code) = $this->formatException($message, $code);

        parent::__construct($message, $code, $previous);
    }

    protected function formatException($message, $code): array
    {
        if (array_key_exists($code, $this->codes)) {
            $definitions = $this->codes[$code];

            foreach ($definitions as $definition) {
                if (array_key_exists('regexp', $definition)) {
                    $definition['message'] = $this->replaceExceptionMessage($message, $definition['message'], $definition['regexp']);
                    return [$definition['message'], $definition['code']];
                }
            }
        }

        return [$message, (int)$code];
    }

    private function replaceExceptionMessage(string $message, string $replacement, string $regexp): string
    {
        preg_match_all("/{$regexp}/", $message, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $replacement = str_replace('{table}', $match[1], $replacement);
        }

        return $replacement;
    }
}
