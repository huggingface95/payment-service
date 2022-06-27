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
    public function create($root, array $args)
    {
        $memberId = ApplicantIndividualLabel::DEFAULT_MEMBER_ID;
        $args['member_id'] = $memberId;
        $label = ApplicantIndividualLabel::create($args);

        return $label;
    }

    /**
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function update($root, array $args)
    {
        $label = ApplicantIndividualLabel::find($args['id']);
        $memberId = ApplicantIndividualLabel::DEFAULT_MEMBER_ID;
        $args['member_id'] = $memberId;
        $label->update($args);

        return $label;
    }

    /**
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function delete($root, array $args)
    {
        try {
            $applicantLabel = ApplicantIndividualLabel::with('applicants')->find($args['id']);
            if (! $applicantLabel) {
                throw new InvalidArgument("Label {$args['id']} not  found");
            }

            if ($applicantLabel->applicants->isNotEmpty()) {
                if (! isset($args['deleteAnyway']) || $args['deleteAnyway'] === false) {
                    throw new InvalidArgument('Label is used other applicants');
                }
            }

            $applicantLabel->delete();

            return $applicantLabel;
        } catch (\Exception $exception) {
            throw new InvalidArgument($exception->getMessage());
        }
    }
}
