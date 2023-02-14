<?php

namespace App\Providers;

use App\Models\PriceListFeeScheduled;
use App\Repositories\AccountRepository;
use App\Repositories\EmailRepository;
use App\Repositories\FileRepository;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Repositories\Interfaces\EmailRepositoryInterface;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\Interfaces\JWTRepositoryInterface;
use App\Repositories\Interfaces\PriceListFeeScheduledRepositoryInterface;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Repositories\Interfaces\VvRepositoryInterface;
use App\Repositories\JWTRepository;
use App\Repositories\PriceListFeeScheduledRepository;
use App\Repositories\TransferOutgoingRepository;
use App\Repositories\VvRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
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

        $this->app->bind(
            EmailRepositoryInterface::class,
            EmailRepository::class
        );

        $this->app->bind(
            JWTRepositoryInterface::class,
            JWTRepository::class
        );

        $this->app->bind(
            TransferOutgoingRepositoryInterface::class,
            TransferOutgoingRepository::class
        );

        $this->app->bind(
            AccountRepositoryInterface::class,
            AccountRepository::class
        );

        $this->app->bind(
            PriceListFeeScheduledRepositoryInterface::class,
            PriceListFeeScheduledRepository::class
        );   
    }
}
