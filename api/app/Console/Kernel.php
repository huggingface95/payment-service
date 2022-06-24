<?php

namespace App\Console;

use App\Console\Commands\NotificationsCommand;
use App\Console\Commands\SendEmailCommand;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        NotificationsCommand::class,
        SendEmailCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('notifications:send')->everyFiveMinutes();
//        $schedule->command('notifications:send')->everyMinute();
    }
}
