<?php

namespace App\GraphQL\Mutations;


use App\Models\ApplicantIndividualLabel;
use GraphQL\Exception\InvalidArgument;


class ApplicantLabelMutator
{

    /**
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function delete($root, array $args)
    {
        if ($args['deleteAnyway']) {
            $applicantLabel = ApplicantIndividualLabel::find($args['id']);
            return $applicantLabel->delete();
        } else {
            throw new InvalidArgument("Label is used other applicants");
        }
    }

}
