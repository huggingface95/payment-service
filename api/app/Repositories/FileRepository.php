<?php

namespace App\Repositories;

use App\DTO\Vv\VvPostBackResponse;
use App\Exceptions\RepositoryException;
use App\Models\Files;
use App\Repositories\Interfaces\FileRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class FileRepository extends Repository implements FileRepositoryInterface
{
    protected function model(): string
    {
        return Files::class;
    }

    /**
     * @throws RepositoryException
     */
    public function saveFile(VvPostBackResponse $response): Model|Builder
    {
        return $this->query()->create([
            'file_name' => $response->data->file_name,
            'mime_type' => $response->data->mime_type,
            'size' => $response->data->size,
            'entity_type' => "file",
            'author_id' => $response->authorId,
            'storage_path' => $response->data->storage_path,
            'storage_name' => $response->data->storage_name,
            'link' => 'https://dev.storage.docudots.com/' . $response->data->full_name,
        ]);
    }

}
