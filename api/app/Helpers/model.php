<?php

use App\Models\BaseModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

if (! function_exists('getAllModels')) {
    function getAllModels(): Collection
    {
        $path = app_path().'/Models';
        $models = collect(File::allFiles($path))
            ->map(function ($item) {
                $path = $item->getRelativePathName();
                $class = sprintf('\%s\%s', 'App\Models', strtr(substr($path, 0, strrpos($path, '.')), '/', '\\'));

                return $class;
            })
            ->filter(function ($class) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new \ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(BaseModel::class) &&
                        ! $reflection->isAbstract();
                }

                return $valid;
            });

        return $models->values();
    }
}
