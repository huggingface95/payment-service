<?php

namespace App\Repositories;

use App\Models\CommissionPriceList;
use App\Models\Region;
use App\Models\TransferIncoming;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TransferIncomingRepository extends Repository implements TransferIncomingRepositoryInterface
{
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
            $model->files()->detach();
            $model->files()->attach(
                $data,
                ['transfer_type' => class_basename(TransferIncoming::class)]
            );
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

    public function getPriceListIdByArgs(array $args, string $clientType): int|null
    {
        $regionId = Region::query()
            ->join('region_countries', 'regions.id', '=', 'region_countries.region_id')
            ->where('region_countries.country_id', '=', $args['sender_country_id'])
            ->where('regions.company_id', '=', $args['company_id'])
            ->first()?->id;

        $priceListId = CommissionPriceList::query()
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
            ->where('region_id', '=', $regionId)
            ->first()?->id;

        return $priceListId;
    }
}
