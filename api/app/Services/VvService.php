<?php

namespace App\Services;

use App\DTO\TransformerDTO;
use App\DTO\Vv\Request\VvGetLinkRequest;
use App\DTO\Vv\Request\VvRegisterRequest;
use App\DTO\Vv\Response\VvGetLinkResponse;
use App\DTO\Vv\Response\VvRegisterResponse;
use App\DTO\Vv\VvConfig;
use App\DTO\Vv\VvPostBackResponse;
use App\Models\Files;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\Interfaces\VvRepositoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;
use GuzzleHttp\Exception\RequestException;

class VvService
{
    const DETECTION = "detection";
    const RECORDING = "recording";

    use ProvidesConvenienceMethods;

    protected VvRepositoryInterface $vvRepository;

    protected FileRepositoryInterface $fileRepository;

    protected Client $client;

    private VvConfig $config;

    public function __construct(VvRepositoryInterface $vvRepository, FileRepositoryInterface $fileRepository, Client $client)
    {
        $this->loadConfig();
        $this->vvRepository = $vvRepository;
        $this->fileRepository = $fileRepository;
        $this->client = $client;
    }

    private function loadConfig(): void
    {
        $this->config = TransformerDTO::transform(VvConfig::class, config('vv'), config('app'));
    }

    /**
     * @throws GuzzleException
     */
    public function sendRegisterRequest(VvRegisterRequest $registerRequest): VvRegisterResponse
    {
        try {
            $response = $this->client->get($registerRequest->url, ['headers' => $registerRequest->headers]);
        } catch (RequestException $e) {
            $response = $e->getResponse();
        }

        return TransformerDTO::transform(VvRegisterResponse::class, $response);
    }

    /**
     * @throws GuzzleException
     */
    public function sendGetLinkRequest(VvGetLinkRequest $getLinkRequest): VvGetLinkResponse
    {
        try {
            $response = $this->client->post($getLinkRequest->url, [
                'headers' => $getLinkRequest->headers,
                'json' => $getLinkRequest->inputs,
            ]);
        } catch (RequestException $e) {
            $response = $e->getResponse();
        }

        return TransformerDTO::transform(VvGetLinkResponse::class, $response);
    }

    /**
     * @throws GuzzleException
     */
    public function getLink(int $id, string $action): string
    {
        $request = $this->vvRepository->getDtoGetLinkRequest($id, $action, $this->config);

        $response = $this->sendGetLinkRequest($request);

        if ($response->status == 200) {
            return $response->url;
        }

        return '';
    }


    /**
     * @throws GuzzleException
     */
    public function registerCompany(int $id): bool
    {
        $request = $this->vvRepository->getDtoRegisterCompanyRequest($id, $this->config);
        $response = $this->sendRegisterRequest($request);

        if ($response->status == 200) {
            return $this->vvRepository->saveToken($id, $response->token);
        }

        return false;
    }

    public function checkToken(?string $token): bool
    {
        if (is_string($token)) {
            return $this->vvRepository->hasToken($token);
        }

        return false;
    }

    protected function generateToken(): string
    {
        return Str::random(32);
    }


    /**
     * @throws ValidationException
     */
    public function validationPostBack(Request $request): void
    {
        $this->validate($request, [
            'action' => 'required',
            'status' => 'required',
            'data' => 'required',
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function savePostBackData(Request $request): void
    {
        $this->validationPostBack($request);

        $response = TransformerDTO::transform(VvPostBackResponse::class, $request->all());

        if ($response->action == self::RECORDING) {

            $this->fileRepository->saveFile($response);

        } elseif ($response->action == self::DETECTION) {
            //TODO Save if detection true
        }

    }
}
