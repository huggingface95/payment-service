<?php

namespace App\GraphQL\Mutations;

use App\Enums\ApplicantTypeEnum;
use App\Exceptions\GraphqlException;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectMutator extends BaseMutator
{
    /**
     * @param    $root
     * @param  array  $args
     * @return mixed
     *
     * @throws \Throwable
     */
    public function create($root, array $args): Project
    {
        try {
            DB::beginTransaction();
            /** @var Project $project */
            $project = Project::create($args);

            $this->createApplicantTypes($project, $args['project_settings'] ?? []);
            $this->createPaymentProviders($project);

            DB::commit();

            return $project;
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw new GraphqlException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param    $root
     * @param  array  $args
     * @return mixed
     *
     * @throws GraphqlException
     */
    public function update($root, array $args): Project
    {
        try {
            DB::beginTransaction();

            /** @var Project $project */
            $project = Project::find($args['id']);
            $project->update($args);
            $project->projectSettings()->delete();
            $this->createApplicantTypes($project, $args['project_settings'] ?? []);

            DB::commit();

            return $project;
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw new GraphqlException($exception->getMessage(), $exception->getCode());
        }
    }

    private function createApplicantTypes(Project $project, array $projectSettings): void
    {
        $settings = [$projectSettings, [['applicant_type' => ApplicantTypeEnum::INDIVIDUAL->toString()], ['applicant_type' => ApplicantTypeEnum::COMPANY->toString()]]];

        collect($settings)->flatten(1)->unique(function ($item) {
            return $item['applicant_type'];
        })->each(function ($setting) use ($project) {
            $project->projectSettings()->create($setting);
        });
    }

    private function createPaymentProviders(Project $project): void
    {
        $project->paymentProviders()->saveMany($project->company->paymentProviders);
        $project->paymentProvidersIban()->saveMany($project->company->paymentProvidersIban);
    }
}
