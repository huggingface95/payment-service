<?php

namespace App\Providers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Mail\Mailer;
use Illuminate\Support\ServiceProvider;
use Swift_Mailer;
use Swift_SmtpTransport;

class MailServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->bind('smtp.dynamic.mailer', function ($app, array $config) {
            $transport = new Swift_SmtpTransport($config['host'], $config['port']);
            $transport->setUsername($config['username']);
            $transport->setPassword($config['password']);
            $transport->setEncryption($config['security']);

            $swift_mailer = new Swift_Mailer($transport);


            $mailer = new Mailer('smtp', app(Factory::class), $swift_mailer, $app->get('events'));
            $mailer->alwaysFrom($config['from'], $config['subject']);
            return $mailer;
        });
        parent::register();
    }
}
