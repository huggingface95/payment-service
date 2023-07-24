<?php

namespace App\Repositories;

use App\Exceptions\GraphqlException;
use App\Models\CommissionPriceList;
use App\Models\Files;
use App\Models\Region;
use App\Models\TransferIncoming;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Services\FileService;

class TransferIncomingRepository extends Repository implements TransferIncomingRepositoryInterface
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        parent::__construct();

        $this->fileService = $fileService;
    }

    protected function model(): string
    {
        return TransferIncoming::class;
    }

    public function findById(int $id): Model|Builder|null
    {
        return $this->find(['id' => $id]);
    }

    public function attachFileById(Model|Builder $model, array $data): Model|Builder|null
    {
        if (isset($data)) {
            $files = $model->files()->pluck('files.id')->toArray();
            $filesToAdd = array_diff($data, $files);

            $model->files()->attach($filesToAdd, ['transfer_type' => class_basename(TransferIncoming::class)]);
        }

        return $model;
    }

    public function detachFileById(Model|Builder $model, array $data): Model|Builder|null
    {
        if (! empty($data)) {
            foreach ($data as $fileId) {
                $file = Files::find($fileId);
                if ($file) {
                    $file->delete();
                    $this->fileService->deleteFile($file);
                }
            }
        }

        return $model;
    }

    public function create(array $data): Model|Builder
    {
        return $this->query()->create($data);
    }

    public function createWithSwift(array $data): Model|Builder
    {
        $transfer = $this->query()->create($data);

        if (isset($data['transfer_swift'])) {
            $transfer->transferSwift()->create(
                array_merge(
                    $data['transfer_swift'],
                    ['transfer_type' => class_basename(TransferIncoming::class)]
                )
            );
        }

        return $transfer;
    }

    public function update(Model|Builder $model, array $data): Model|Builder
    {
        $model->update($data);

        return $model;
    }

    public function updateWithSwift(Model|Builder $model, array $data): Model|Builder
    {
        $model->update($data);

        if (isset($data['transfer_swift'])) {
            $model->transferSwift()->update(
                array_merge(
                    $data['transfer_swift'],
                    ['transfer_type' => class_basename(TransferIncoming::class)]
                )
            );
        }

        return $model;
    }

    public function getCommissionPriceListIdByArgs(array $args, string $clientType): int|null
    {
        return CommissionPriceList::query()
            ->where('company_id', '=', $args['company_id'])
            ->where('commission_template_id', '=', function ($query) use ($args, $clientType) {
                $query->select('project_settings.commission_template_id')
                    ->from('projects')
                    ->join('project_settings', 'projects.id', '=', 'project_settings.project_id')
                    ->where('projects.id', '=', $args['project_id'])
                    ->where('applicant_type', '=', $clientType);
            })
            ->where('provider_id', '=', $args['payment_provider_id'])
            ->where('payment_system_id', '=', $args['payment_system_id'])
            ->where('region_id', '=', $args['region_id'])
            ->first()?->id;
    }

    public function getRegionIdByArgs(array $args): int|null
    {
        return Region::query()
            ->join('region_countries', 'regions.id', '=', 'region_countries.region_id')
            ->where('region_countries.country_id', '=', $args['sender_country_id'])
            ->where('regions.company_id', '=', $args['company_id'])
            ->first()?->id;
    }
}
