<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\BaseModel;
use App\Models\EmailNotification;
use App\Models\GroupRole;


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
        ($args['group_type_id'] == GroupRole::MEMBER) ? $args['type'] = EmailNotification::ADMINISTRATION : $args['type'] = EmailNotification::CLIENT;
        /** @var EmailNotification $emailNotification */
        $emailNotification =  EmailNotification::create($args);
        $emailNotification->templates()->sync($templates, true);

        if (isset($args['client_id'])){
            $emailNotification->recipient_type = EmailNotification::RECIPIENT_PERSON;
            $emailNotification->save();
            $emailNotification->clientable()->sync($args['client_id'], true);
        }
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

        if (isset($args['client_id'])){
            $emailNotification->recipient_type = EmailNotification::RECIPIENT_PERSON;
            $emailNotification->clientable()->sync($args['client_id'], true);
        }
        ($args['group_type_id'] == GroupRole::MEMBER) ? $args['type'] = EmailNotification::ADMINISTRATION : $args['type'] = EmailNotification::CLIENT;

        $emailNotification->update($args);
        return $emailNotification;
    }






}
