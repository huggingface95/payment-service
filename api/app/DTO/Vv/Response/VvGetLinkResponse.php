<?php

namespace App\DTO\Vv\Response;

use Psr\Http\Message\ResponseInterface;

class VvGetLinkResponse
{
    public int $status;

    public string $url;

    public static function transform(ResponseInterface $response): self
    {
        $dto = new self();
        $dto->status = $response->getStatusCode();
        if ($dto->status == 200) {
            $responseDecode = json_decode($response->getBody());
            $dto->url = $responseDecode->msg;
        }

        return $dto;
    }
}
