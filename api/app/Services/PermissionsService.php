<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class PermissionsService extends AbstractService
{

    public const PREFIX = 'PERMISSION_';

    /**
     * Get permissions list
     *
     * @param  Collection  $arrs
     * @return array
     */
    public function getPermissionsList(Collection $arrs): array
    {
        $result = [];

        foreach ($arrs as $arr) {
            $permissionWithRights = self::PREFIX . strtoupper(Str::snake(str_replace(':', '', $arr['name'])));

            $result[] = $permissionWithRights;
        }

        $result = array_values(array_unique($result));

        return $result;
    }

}
