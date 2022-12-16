<?php

namespace App\Services;

use App\Models\Files;
use App\Models\Members;
use Illuminate\Support\Facades\Storage;

class FileService extends AbstractService
{
    public function uploadFile($request): Files
    {
        $file = $request->file('file');
        $original_name = $file->getClientOriginalName();
        $entity_type = $request->post('entity_type');
        $author_id = $request->post('author_id');
        $filepath = $author_id . '/' . $entity_type;
        $store = $file->store($filepath, 's3');
        $filename = explode('/', $store);
        $data = [
            'file_name' => $original_name,
            'mime_type' => $file->getClientMimeType(),
            'resolution' => $this->getImageResolution($file),
            'size' => $file->getSize(),
            'entity_type' => $entity_type,
            'author_id' => $author_id,
            'storage_path' => '/' . $filepath . '/',
            'storage_name' => $filename[2],
            'link' => 'https://dev.storage.docudots.com/' . $filepath . '/' . $filename[2],
            'member_id' => $this->getMemberId(),
        ];

        $fileDb = Files::create($data);

        $exists = Storage::disk('s3')->exists($filepath . '/' . $filename[2]);
        ($exists and $fileDb) ? $link = 'https://dev.storage.docudots.com/' . $filepath . '/' . $filename[2] . '' : Storage::disk('s3')->delete($filepath . '/' . $filename[2]);

        return $fileDb;
    }

    public function getImageResolution($file): string | null
    {
        $resolution = getimagesize($file);

        return !empty($resolution) ? $resolution[0] . 'x' . $resolution[1] : null;
    }

    public function getMemberId(): int | null
    {
        $authUser = auth()->user();
        if ($authUser && ($authUser instanceof Members)) {
            return $authUser->id;
        }

        return null;
    }

}
