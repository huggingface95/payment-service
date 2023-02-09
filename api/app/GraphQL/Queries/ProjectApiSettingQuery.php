<?php

namespace App\GraphQL\Queries;

use App\Exceptions\GraphqlException;
use App\Models\ProjectApiSetting;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class ProjectApiSettingQuery
{
    /**
     * @throws GraphqlException
     */
    public function getPassword($root, array $args): array
    {
        $setting = ProjectApiSetting::find($args['id']);
        if (! $setting) {
            throw new GraphqlException('Project api setting not found', 'not found', 404);
        }

        try {
            $decryptedPassword = Crypt::decryptString($setting->password);
        } catch (DecryptException) {
            throw new GraphqlException('Decryption data error', 'internal');
        } catch (\Throwable) {
            throw new GraphqlException('Empty password', 'internal');
        }

        return [
            'id' => $setting->id,
            'password' => $decryptedPassword,
        ];
    }
}
