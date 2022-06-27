<?php

namespace App\Models\Traits;

use App\Models\PermissionFilter;

trait PermissionFilterData
{
    public static function getPermissionFilter($mode, $action, $table, $conditions)
    {
        return PermissionFilter::query()->with('binds')->where('mode', $mode)
            ->where(function ($q) use ($action) {
                return $action ? $q->where('action', $action) : $q->whereNull('action');
            })
            ->where('table', $table)
            ->where(function ($query) use ($conditions) {
                foreach ($conditions as $column => $value) {
                    $query->orWhere(function ($query) use ($column, $value) {
                        $query->where('column', $column)->where('value', $value);
                    });
                }
            })->get();
    }


}
