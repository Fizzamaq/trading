<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Schedule the monthly profit distribution command on the last day of each month at 23:59
        $schedule->command('profit:distribute')
                 ->monthlyOn(31, '23:59')
                 ->description('Distribute monthly profit among investors');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // Load commands from the Commands directory
        $this->load(__DIR__.'/Commands');

        // Load console routes if any (optional)
        require base_path('routes/console.php');
    }
}
