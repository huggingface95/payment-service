<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\Project;

class ProjectApiSettingMutator extends BaseMutator
{
    public function update($root, array $args): \Illuminate\Database\Eloquent\Collection
    {
        /** @var Project $project */
        $project = Project::query()->find($args['project_id']);

        if (! $project) {
            throw new GraphqlException('Project not found', 'not found', 404);
        }

        foreach ($args['input'] as $setting) {
            $apiSettings = $project->projectApiSettings()
                ->where('provider_id', $setting['provider_id'])
                ->where('provider_type', $setting['provider_type'])
                ->first();

            if (! $apiSettings) {
                throw new GraphqlException('ProjectApiSettings not found', 'not found', 404);
            }

            $apiSettings->update($setting);
        }

        return $project->projectApiSettings()->get();
    }
}
