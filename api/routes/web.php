<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->group([
    //    'middleware' => 'api',
    'prefix' => 'auth',
], function () use ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->post('me', 'AuthController@me');
    $router->get('2fareg', 'AuthController@show2FARegistrationInfo');
    $router->post('2fareg', 'AuthController@activate2FA');
    $router->post('2faverify', 'AuthController@verify2FA');
    $router->post('2fadisable', 'AuthController@disable2FA');
    $router->get('2facodes', 'AuthController@generateBackupCodes');
    $router->post('2facodes', 'AuthController@storeBackupCodes');
});

$router->group([
    'prefix' => 'vv',
    'namespace' => 'Vv',
], function () use ($router){
    $router->post('postback', 'VvController@postback');
});

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('api/files', ['uses' => 'FilesController@upload']);
$router->get('api/pdf', ['uses' => 'FilesController@createpdf']);
$router->post('api/email', ['uses' => 'FilesController@sendreq']);
$router->post('api/sms', ['uses' => 'SmsController@send']);

$router->get('/test', ['uses' => 'ExampleController@index']);

$router->post('/password/email', 'PasswordController@postEmail');
$router->post('/password/reset/{token}', 'PasswordController@postReset');
$router->get('/password/reset/{token}', ['as'=>'password.reset', function () {
}]);
