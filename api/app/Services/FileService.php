<?php

namespace App\Services;

use App\Models\Files;
use App\Models\Members;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService extends AbstractService
{
    public const S3_URL = 'https://dev.storage.docudots.com/';

    public function uploadFile(Request $request, UploadedFile $file): Files
    {
        $entityType = $request->post('entity_type');
        $authorId = $request->post('author_id');
        $filepath = $authorId . '/' . $entityType;
        $store = $file->store($filepath, 's3');
        $filename = explode('/', $store);
        $data = [
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'resolution' => $this->getImageResolution($file),
            'size' => $file->getSize(),
            'entity_type' => $entityType,
            'author_id' => $authorId,
            'storage_path' => '/' . $filepath . '/',
            'storage_name' => $filename[2],
            'link' => self::S3_URL . $filepath . '/' . $filename[2],
            'member_id' => $this->getMemberId(),
        ];

        $fileDb = Files::create($data);
        $exists = Storage::disk('s3')->exists($filepath . '/' . $filename[2]);

        if (!$exists || !$fileDb) {
            info('delete');
            Storage::disk('s3')->delete($filepath . '/' . $filename[2]);
        }

        return $fileDb;
    }

    public function getImageResolution($file): string|null
    {
        $resolution = getimagesize($file);

        return !empty($resolution) ? $resolution[0] . 'x' . $resolution[1] : null;
    }

    public function getMemberId(): int|null
    {
        $authUser = auth()->user();
        if ($authUser && ($authUser instanceof Members)) {
            return $authUser->id;
        }

        return null;
    }
}
