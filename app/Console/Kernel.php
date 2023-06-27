<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected $commands = [
        Commands\EmailCron::class,
        Commands\TokensDeletedCron::class,
        Commands\AssignTask::class,
        Commands\DbBackup::class,
    ];
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('email:cron')->everyMinute();
        $schedule->command('tokens:cron')->everyMinute();
        $schedule->command('backup:cron')->daily();
        $schedule->command('assigntask:cron')->everyMinute();
        
        // $schedule->command('backup:cron')->everyMinute();
        // $schedule->command('db:backup')->daily();
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}