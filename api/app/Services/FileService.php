<?php

namespace App\Services;

use App\Enums\GuardEnum;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileService extends AbstractService
{
    public const S3_URL = 'https://dev.storage.docudots.com/';

    public function uploadFile(Request $request, UploadedFile $file): Files
    {
        $entityType = $request->post('entity_type');
        $authorId = $request->post('author_id');
        $filepath = $authorId.'/'.$entityType;
        $store = $file->store($filepath, 's3');
        $filename = explode('/', $store);
        $data = [
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'resolution' => $this->getImageResolution($file),
            'size' => $file->getSize(),
            'entity_type' => $entityType,
            'author_id' => $authorId,
            'storage_path' => '/'.$filepath.'/',
            'storage_name' => $filename[2],
            'link' => self::S3_URL.$filepath.'/'.$filename[2],
            'user_id' => $this->getUserId(),
            'user_type' => $this->getUserType(),
        ];

        $fileDb = Files::create($data);
        $exists = Storage::disk('s3')->exists($filepath.'/'.$filename[2]);

        if (! $exists || ! $fileDb) {
            Storage::disk('s3')->delete($filepath.'/'.$filename[2]);
        }

        return $fileDb;
    }

    public function getImageResolution($file): string|null
    {
        $resolution = getimagesize($file);

        return ! empty($resolution) ? $resolution[0].'x'.$resolution[1] : null;
    }

    public function getUserType(): string|null
    {
        if (Auth::guard('api')->check()){
            return GuardEnum::GUARD_MEMBER->toString();
        } elseif (Auth::guard('api_client')->check()){
            return GuardEnum::GUARD_INDIVIDUAL->toString();
        } elseif (Auth::guard('api_corporate')->check()){
            return GuardEnum::GUARD_CORPORATE->toString();
        }

        return null;
    }

    public function getUserId(): int|null
    {
        return Auth::guard('api')->id() ?? Auth::guard('api_client')->id() ?? Auth::guard('api_corporate')->id();
    }
}
