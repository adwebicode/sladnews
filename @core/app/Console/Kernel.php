<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        //
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('blog:publish')
            ->everyMinute();

        $schedule->command('feed:automation')
            ->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
