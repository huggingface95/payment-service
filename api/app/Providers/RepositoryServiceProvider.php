<?php

namespace App\Providers;

use App\Repositories\FileRepository;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\Interfaces\VvRepositoryInterface;
use App\Repositories\VvRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(
            VvRepositoryInterface::class,
            VvRepository::class
        );

        $this->app->bind(
            FileRepositoryInterface::class,
            FileRepository::class
        );
    }
}
