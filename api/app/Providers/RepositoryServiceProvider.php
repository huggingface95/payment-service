<?php

namespace App\Providers;

use App\Repositories\AccountRepository;
use App\Repositories\CheckLimitRepository;
use App\Repositories\EmailRepository;
use App\Repositories\FileRepository;
use App\Repositories\GraphqlManipulateSchemaRepository;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Repositories\Interfaces\CheckLimitRepositoryInterface;
use App\Repositories\Interfaces\EmailRepositoryInterface;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\Interfaces\GraphqlManipulateSchemaRepositoryInterface;
use App\Repositories\Interfaces\JWTRepositoryInterface;
use App\Repositories\Interfaces\PriceListFeeScheduledRepositoryInterface;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Repositories\Interfaces\VvRepositoryInterface;
use App\Repositories\JWTRepository;
use App\Repositories\PriceListFeeScheduledRepository;
use App\Repositories\TransferIncomingRepository;
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
            TransferIncomingRepositoryInterface::class,
            TransferIncomingRepository::class
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

        $this->app->bind(
            GraphqlManipulateSchemaRepositoryInterface::class,
            GraphqlManipulateSchemaRepository::class
        );

        $this->app->bind(
            CheckLimitRepositoryInterface::class,
            CheckLimitRepository::class
        );
    }
}
