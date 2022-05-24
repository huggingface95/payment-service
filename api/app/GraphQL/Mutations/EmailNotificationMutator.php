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
        $emailNotification->templates()->attach($templates);
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
            $emailNotification->templates()->detach();
            $emailNotification->templates()->attach($args['templates']);
            unset($args['templates']);
        }

        $emailNotification->update($args);
        return $emailNotification;
    }






}
