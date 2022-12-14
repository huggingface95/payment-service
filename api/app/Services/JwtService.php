<?php

namespace App\Services;

use App\Repositories\Interfaces\JWTRepositoryInterface;
use App\Repositories\JWTRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use InvalidArgumentException;
use stdClass;
use UnexpectedValueException;

class JwtService extends JWT
{
    public JWTRepository $repository;

    public function __construct(JWTRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function parseJWT(string $token): ?stdClass
    {
        foreach (['members', 'applicant'] as $provider) {
            $personalClient = $this->repository->getPersonalAccessToken($provider);
            if ($personalClient) {
                return self::decode($token, new Key($personalClient->secret, env('JWT_ALGO')));
            }
        }
        return null;
    }

    public static function decode(
        string $jwt,
               $keyOrKeyArray
    ): stdClass
    {
        if (empty($keyOrKeyArray)) {
            throw new InvalidArgumentException('Key may not be empty');
        }
        $tks = \explode('.', $jwt);
        if (\count($tks) !== 3) {
            throw new UnexpectedValueException('Wrong number of segments');
        }
        $bodyB64 = $tks[1];

        $payloadRaw = static::urlsafeB64Decode($bodyB64);
        if (null === ($payload = static::jsonDecode($payloadRaw))) {
            throw new UnexpectedValueException('Invalid claims encoding');
        }

        return $payload;
    }
}
