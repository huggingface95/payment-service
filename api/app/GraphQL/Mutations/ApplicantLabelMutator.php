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
        try {
            $applicantLabel = ApplicantIndividualLabel::with('applicants')->find($args['id']);
            if (!$applicantLabel) {
                throw new InvalidArgument("Label {$args['id']} not  found");
            }

            if ($applicantLabel->applicants->isNotEmpty()) {
                if (!isset($args['deleteAnyway']) || $args['deleteAnyway'] === false) {
                    throw new InvalidArgument("Label is used other applicants");
                }
            }

            $applicantLabel->delete();
            return $applicantLabel;
        } catch (\Exception $exception)
        {
            throw new InvalidArgument($exception->getMessage());
        }

    }

}
