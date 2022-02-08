<?php

namespace App\GraphQL\Mutations;


use App\Models\ApplicantCompany;
use App\Models\ApplicantCompanyLabel;
use GraphQL\Exception\InvalidArgument;


class ApplicantCompanyLabelMutator
{

    /**
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function create($root, array $args)
    {
        $memberId = ApplicantCompanyLabel::DEFAULT_MEMBER_ID;
        $args['member_id'] = $memberId;
        $label = ApplicantCompanyLabel::create($args);

        return $label;
    }

    /**
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function update($root, array $args)
    {
        $label = ApplicantCompanyLabel::find($args['id']);
        $memberId = ApplicantCompanyLabel::DEFAULT_MEMBER_ID;
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
            $applicantLabel = ApplicantCompanyLabel::with('applicants')->find($args['id']);
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

    /**
     * Return a value for the field.
     *
     * @param  @param  null  $root Always null, since this field has no parent.
     * @param  array<string, mixed>  $args The field arguments passed by the client.
     * @return mixed
     */

    public function attach($root, array $args)
    {
        $applicantCompanyLable = ApplicantCompany::where('id', '=', $args['applicant_company_id'])->first();

        if (isset($args['applicant_company_label_id'])) {
            $applicantCompanyLable->labels()->detach();
            $applicantCompanyLable->labels()->attach($args['applicant_company_label_id']);
        }

        return $applicantCompanyLable;
    }

    public function detach($root, array $args)
    {
        $applicantCompanyLable = ApplicantCompany::where('id', '=', $args['applicant_company_id'])->first();
        $applicantCompanyLable->labels()->detach($args['applicant_company_label_id']);
        return $applicantCompanyLable;
    }

}
