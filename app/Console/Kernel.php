<?php

namespace App\Console;

use App\Jobs\DeleteMedia;
use App\Jobs\SalesRefund;
use App\Jobs\RebillWallet;
use App\Jobs\PostScheduled;
use App\Jobs\RebillCardinity;
use App\Jobs\ExpiredAdvertising;
use App\Jobs\LiveStreamingPrivateExpired;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('queue:work --tries=3 --timeout=8600')
            ->cron('* * * * *')
            ->withoutOverlapping();

        $schedule->command('cache:clear')
            ->weekly()
            ->withoutOverlapping();

        $schedule->job(new DeleteMedia)->everySixHours();
        $schedule->job(new RebillWallet)->hourly();
        $schedule->job(new RebillCardinity)->hourly();
        $schedule->job(new PostScheduled)->everyMinute();
        $schedule->job(new SalesRefund)->daily();
        $schedule->job(new ExpiredAdvertising)->hourly();
        $schedule->job(new LiveStreamingPrivateExpired)->everySixHours();
        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
