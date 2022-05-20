<?php

use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3Adapter;
use League\Flysystem\Filesystem;

require_once __DIR__ . '/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '') {
        return app()->basePath().DIRECTORY_SEPARATOR.'config' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->instance('path.config', app()->basePath() . DIRECTORY_SEPARATOR . 'config');
$app->instance('path.storage', app()->basePath() . DIRECTORY_SEPARATOR . 'storage');

// $app->withFacades();

// $app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Config Files
|--------------------------------------------------------------------------
|
| Now we will register the "app" configuration file. If the file exists in
| your configuration directory it will be loaded; otherwise, we'll load
| the default version. You may register other files below as needed.
|
*/
$app->withFacades();
$app->withEloquent();
$app->configure('app');
$app->configure('permission');
$app->configure('lighthouse');
$app->configure('lighthouse-graphql-jwt');
$app->configure('swagger-lume');
$app->configure('graphql-playground');
$app->configure('filesystems');
$app->configure('dompdf');
$app->configure('mail');
$app->configure('queue');
/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/



/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
$app->register(\Illuminate\Auth\Passwords\PasswordResetServiceProvider::class);
//$app->register(Spatie\Permission\PermissionServiceProvider::class);
 //$app->register(Wimil\LighthouseGraphqlJwtAuth\LighthouseGraphqlJwtAuthServiceProvider::class);
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);
$app->register(Spatie\Permission\PermissionServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);
$app->register(\Nuwave\Lighthouse\LighthouseServiceProvider::class);
$app->register(\Nuwave\Lighthouse\Pagination\PaginationServiceProvider::class);
$app->register(\Nuwave\Lighthouse\WhereConditions\WhereConditionsServiceProvider::class);
$app->register(\Nuwave\Lighthouse\OrderBy\OrderByServiceProvider::class);
$app->register(\Nuwave\Lighthouse\GlobalId\GlobalIdServiceProvider::class);
$app->register(\Nuwave\Lighthouse\Validation\ValidationServiceProvider::class);
$app->register(\Nuwave\Lighthouse\Auth\AuthServiceProvider::class);
$app->register(\SwaggerLume\ServiceProvider::class);
$app->register(MLL\GraphQLPlayground\GraphQLPlaygroundServiceProvider::class);
$app->register(Barryvdh\DomPDF\ServiceProvider::class);
$app->register(\Illuminate\Mail\MailServiceProvider::class);
$app->register(\PhpClickHouseLaravel\ClickhouseServiceProvider::class);
$app->register(Illuminate\Mail\MailServiceProvider::class);
$app->register(Illuminate\Redis\RedisServiceProvider::class);
$app->register(PragmaRX\Google2FALaravel\ServiceProvider::class);
$app->register(App\Providers\TwoFactorServiceProvider::class);
//$app->register(Laravel\Passport\PassportServiceProvider::class);
$app->register(Dusterio\LumenPassport\PassportServiceProvider::class);
$app->register(Illuminate\Notifications\NotificationServiceProvider::class);


/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/
$app->alias('cache', \Illuminate\Cache\CacheManager::class);
$app->alias('PDF', Barryvdh\DomPDF\Facade::class);
$app->alias('mail.manager', Illuminate\Mail\MailManager::class);
$app->alias('mail.manager', Illuminate\Contracts\Mail\Factory::class);
$app->alias('mailer', Illuminate\Mail\Mailer::class);
$app->alias('Google2FA', \PragmaRX\Google2FALaravel\Facade::class);
$app->alias('Notification', Illuminate\Support\Facades\Notification::class);

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/web.php';
});

return $app;
