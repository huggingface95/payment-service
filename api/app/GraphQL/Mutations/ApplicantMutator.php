<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\ApplicantIndividual;
use App\Models\ClientIpAddress;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApplicantMutator extends BaseMutator
{
    /**
     * Return a value for the field.
     *
     * @param  @param  null  $root Always null, since this field has no parent.
     * @param  array<string, mixed>  $args The field arguments passed by the client.
     * @return mixed
     */
    public function create($root, array $args)
    {
        if (! isset($args['password'])) {
            $password = Str::random(8);
        } else {
            $password = $args['password'];
        }
        $args['password_hash'] = Hash::make($password);
        $args['password_salt'] = Hash::make($password);

        if (isset($args['personal_additional_fields'])) {
            $args['personal_additional_fields'] = $this->setAdditionalField($args['personal_additional_fields']);
        }
        if (isset($args['contacts_additional_fields'])) {
            $args['contacts_additional_fields'] = $this->setAdditionalField($args['contacts_additional_fields']);
        }
        $applicant = ApplicantIndividual::create($args);

        if (isset($args['group_id'])) {
            $applicant->groupRole()->sync([$args['group_id']], true);
        }

        if (isset($args['labels'])) {
            $applicant->labels()->detach($args['labels']);
            $applicant->labels()->attach($args['labels']);
        }

        return $applicant;
    }

    /**
     * Return a value for the field.
     *
     * @param  @param  null  $root Always null, since this field has no parent.
     * @param  array<string, mixed>  $args The field arguments passed by the client.
     * @return mixed
     */
    public function update($root, array $args)
    {
        if (isset($args['password'])) {
            $args['password_hash'] = Hash::make($args['password']);
            $args['password_salt'] = Hash::make($args['password']);
        }

        $applicant = ApplicantIndividual::find($args['id']);
        if (isset($args['personal_additional_fields'])) {
            $args['personal_additional_fields'] = $this->setAdditionalField($args['personal_additional_fields']);
        }
        if (isset($args['profile_additional_fields'])) {
            $args['profile_additional_fields'] = $this->setAdditionalField($args['profile_additional_fields']);
        }
        if (isset($args['contacts_additional_fields'])) {
            $args['contacts_additional_fields'] = $this->setAdditionalField($args['contacts_additional_fields']);
        }
        if (isset($args['labels'])) {
            $applicant->labels()->detach($args['labels']);
            $applicant->labels()->attach($args['labels']);
        }

        if (isset($args['ip_address'])) {
            $ip_address = str_replace(' ', '', explode(',', $args['ip_address']));
            for ($i = 0; $i < count($ip_address); $i++) {
                if (! filter_var($ip_address[$i], FILTER_VALIDATE_IP)) {
                    throw new GraphqlException('Not a valid ip address. Address format xxx.xxx.xxx.xxx and must be comma separated', 'internal', 403);
                }
            }
            if (count($ip_address) > 0) {
                $applicant->ipAddress()->delete();
            }
            foreach ($ip_address as $ip) {
                ClientIpAddress::create([
                    'client_id' => $applicant->id,
                    'ip_address' => $ip,
                    'client_type' => 'App\Models\ApplicantIndividual',
                ]);
            }
        }
        $applicant->update($args);

        if (isset($args['group_id'])) {
            $applicant->groupRole()->sync([$args['group_id']], true);
        }

        return $applicant;
    }

    public function setSecurityPin($_, array $args)
    {
        ApplicantIndividual::where('id', $args['id'])->update(['security_pin'=>str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT)]);

        return $args;
    }
}
