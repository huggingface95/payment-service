<?php

namespace App\Services;

use App\DTO\Vv\VvPostBackResponse;
use App\Models\Files;
use App\Repositories\Interfaces\VvRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class VvService
{
    const DETECTION = "detection";
    const RECORDING = "recording";

    use ProvidesConvenienceMethods;

    protected VvRepositoryInterface $vvRepository;

    protected Files $file;

    public function __construct(VvRepositoryInterface $vvRepository, Files $file)
    {
        $this->vvRepository = $vvRepository;
        $this->file = $file;
    }

    public function checkToken(?string $token): bool
    {
        if (is_string($token)){
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
        if ($vvPostBackResponse->action == self::RECORDING){
            $client = $this->vvRepository->findByToken($vvPostBackResponse->token);

            $this->file->save([
                    'file_name' => $vvPostBackResponse->data->file_name,
                    'mime_type' => $vvPostBackResponse->data->mime_type,
                    'size' => $vvPostBackResponse->data->size,
                    'entity_type' => "file",
                    'author_id' => $client->id,
                    'storage_path' => $vvPostBackResponse->data->storage_path,
                    'storage_name' => $vvPostBackResponse->data->storage_name,
                    'link' => 'https://dev.storage.docudots.com/'.$vvPostBackResponse->data->full_name,
            ]);
        }
        elseif ($vvPostBackResponse->action == self::DETECTION){
            //TODO Save if detection true
        }

    }
}
