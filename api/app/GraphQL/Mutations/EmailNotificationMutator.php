<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\BaseModel;
use App\Models\EmailNotification;


class EmailNotificationMutator extends BaseMutator
{
    /**
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function create($root, array $args)
    {
        $templates = $args['templates'];
        unset($args['templates']);
        $emailNotification =  EmailNotification::create($args);
        $emailNotification->templates()->sync($templates, true);
        $emailNotification->clientable()->sync($args['client_id'], true);
        return $emailNotification;
    }

    /**
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function update($root, array $args)
    {
        $emailNotification = EmailNotification::find($args['id']);
        if (!$emailNotification) {
            throw new GraphqlException('An entry with this email notification does not exist',"not found", 404);
        }
        if ($args['templates']) {
            $emailNotification->templates()->sync($args['templates'], true);
            unset($args['templates']);
        }

        if ($args['client_id']) {
            $emailNotification->clientable()->sync($args['client_id'], true);
        }

        $emailNotification->update($args);
        return $emailNotification;
    }






}
