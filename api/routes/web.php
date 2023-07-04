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
    'prefix' => 'auth',
    'middleware' => 'access',
], function () use ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('me', 'AuthController@me');
    $router->post('refresh', 'AuthController@refresh');
    $router->post('logout', 'AuthController@logout');
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
], function () use ($router) {
    //Example routes register, get-link
    $router->get('register', 'VvController@register');
    $router->get('get-link', 'VvController@getLink');
    $router->post('postback', 'VvController@postback');
});

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group([
    'prefix' => 'api',
    'middleware' => ['auth:api,api_client,api_corporate']
], function () use ($router) {
    $router->post('files', ['uses' => 'FilesController@upload']);
    $router->get('pdf', ['uses' => 'FilesController@createpdf']);
    $router->post('email', ['uses' => 'FilesController@sendreq']);
    $router->post('sms', ['uses' => 'SmsController@send']);
});

$router->get('/test', ['uses' => 'ExampleController@index']);

$router->group([
    'prefix' => 'password',
], function () use ($router) {
    $router->post('email', 'PasswordController@postEmail');
    $router->post('reset/{token}', 'PasswordController@postReset');
    $router->get('reset/{token}', ['as'=>'password.reset', 'uses' => 'PasswordController@resetApplicantPassword']);
    $router->post('change/member', ['as' => 'password.change.member', 'uses' => 'PasswordController@changeMemberPassword']);
    $router->post('change/member/{token}', ['as' => 'password.change.member_by_token', 'uses' => 'PasswordController@changeMemberPasswordByToken']);
});

$router->group([
    'prefix' => 'email',
], function () use ($router) {
    $router->get('verify/{token}/{applicant}', ['as' => 'email.confirm.verify', 'uses' => 'VerifyController@emailVerify']);
    $router->get('change/verify/{token}', ['as' => 'email.confirm.change', 'uses' => 'VerifyController@emailChangeVerify']);
    $router->get('registration/verify/{token}', ['as' => 'email.confirm.registration', 'uses' => 'VerifyController@emailRegistrationVerify']);
});
