<?php

namespace App\Providers;

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
    }
}
