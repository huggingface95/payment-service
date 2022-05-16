<?php
/**
 *
 * User: aitspeko
 * Date: 19/09/2018
 * Time: 17:17
 *
 * Project: lumen-2fa
 */

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Lshtmweb\Lumen2FA\TwoFactorCheckMiddleware;

class TwoFactorServiceProvider extends ServiceProvider
{
        public function boot(Router $router)
        {
                $this->loadMigrationsFrom(__DIR__ . '/migrations');
                $this->publishes([
                    __DIR__ . '/config/lumen2fa.php' => config_path('lumen2fa.php'),
                ]);

                $router->pushMiddlewareToGroup('lumen2fa', TwoFactorCheckMiddleware::class);
        }
}
