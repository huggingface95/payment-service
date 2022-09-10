<?php

namespace App\Services;

use App\DTO\TransformerDTO;
use App\DTO\Vv\Request\VvGetLinkRequest;
use App\DTO\Vv\Request\VvRegisterRequest;
use App\DTO\Vv\Response\VvGetLinkResponse;
use App\DTO\Vv\Response\VvRegisterResponse;
use App\DTO\Vv\VvConfig;
use App\DTO\Vv\VvPostBackResponse;
use App\Models\Companies;
use App\Models\Files;
use App\Repositories\Interfaces\VvRepositoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class VvService
{
    const DETECTION = "detection";
    const RECORDING = "recording";

    use ProvidesConvenienceMethods;

    protected VvRepositoryInterface $vvRepository;

    protected Files $file;

    protected Client $client;

    private VvConfig $config;

    public function __construct(VvRepositoryInterface $vvRepository, Files $file, Client $client)
    {
        $this->loadConfig();
        $this->vvRepository = $vvRepository;
        $this->file = $file;
        $this->client = $client;
    }

    private function loadConfig(): void
    {
        $this->config = TransformerDTO::transform(VvConfig::class, config('vv'), config('app'));
    }

    /**
     * @throws GuzzleException
     */
    public function sendRegisterRequest(VvRegisterRequest $registerRequest): ResponseInterface
    {
        try {
            return $this->client->get($registerRequest->url, ['headers' => $registerRequest->headers]);
        } catch (RequestException $e) {
            return $e->getResponse();
        }
    }

    /**
     * @throws GuzzleException
     */
    public function sendGetLinkRequest(VvGetLinkRequest $getLinkRequest): ResponseInterface
    {
        try {
            return $this->client->post($getLinkRequest->url, [
                'headers' => $getLinkRequest->headers,
                'json' => $getLinkRequest->inputs,
            ]);
        } catch (RequestException $e) {
            return $e->getResponse();
        }
    }

    /**
     * @throws GuzzleException
     */
    public function getLink(Companies $company, string $action): string
    {
        $request = TransformerDTO::transform(VvGetLinkRequest::class, $this->config, $company, $action);

        $response = TransformerDTO::transform(VvGetLinkResponse::class, $this->sendGetLinkRequest($request));

        if ($response->status == 200) {
            return $response->url;
        }

        return '';
    }


    /**
     * @throws GuzzleException
     */
    public function registerCompany(Companies $company): bool
    {
        $request = TransformerDTO::transform(VvRegisterRequest::class, $this->config, $company);

        $response = TransformerDTO::transform(VvRegisterResponse::class, $this->sendRegisterRequest($request));

        if ($response->status == 200) {
            return $this->vvRepository->saveToken($company->id, $response->token);
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

    public function savePostBackData(VvPostBackResponse $vvPostBackResponse): void
    {
        if ($vvPostBackResponse->action == self::RECORDING) {
            $client = $this->vvRepository->findByToken($vvPostBackResponse->token);

            $this->file->save([
                'file_name' => $vvPostBackResponse->data->file_name,
                'mime_type' => $vvPostBackResponse->data->mime_type,
                'size' => $vvPostBackResponse->data->size,
                'entity_type' => "file",
                'author_id' => $client->id,
                'storage_path' => $vvPostBackResponse->data->storage_path,
                'storage_name' => $vvPostBackResponse->data->storage_name,
                'link' => 'https://dev.storage.docudots.com/' . $vvPostBackResponse->data->full_name,
            ]);
        } elseif ($vvPostBackResponse->action == self::DETECTION) {
            //TODO Save if detection true
        }

    }
}
