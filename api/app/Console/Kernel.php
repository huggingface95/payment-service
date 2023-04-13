<?php

namespace App\Console;

use App\Console\Commands\CalculateRevenueCommissionCommand;
use App\Console\Commands\DropTestTables;
use App\Console\Commands\ExecuteFeeScheduledTransferCommand;
use App\Console\Commands\ExecuteWaitingTransferCommand;
use App\Console\Commands\IbanCompanyCommand;
use App\Console\Commands\IbanIndividualStatusCommand;
use App\Console\Commands\NotificationsCommand;
use App\Console\Commands\ResetApplicantBankingAccessUsedLimitCommand;
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
        CalculateRevenueCommissionCommand::class,
        ExecuteFeeScheduledTransferCommand::class,
        ExecuteWaitingTransferCommand::class,
        NotificationsCommand::class,
        SendEmailCommand::class,
        DropTestTables::class,
        IbanCompanyCommand::class,
        IbanIndividualStatusCommand::class,
        ResetApplicantBankingAccessUsedLimitCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('applicant-bancking-access:reset-used-limit')->dailyAt('00:00');
//        $schedule->command('notifications:send')->everyFiveMinutes();
        $schedule->command('iban:individual:approval:email')->everyMinute();
        $schedule->command('transfer:execute-waiting')->daily();
    }
}
