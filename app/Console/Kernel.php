<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\AccountService;
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
        // $schedule->command('inspire')
        //          ->hourly();
        // $schedule->command('periksa:rekeningdonasi')
        // ->cron('* * * * * *');
        // ->everyMinute(); 
        #periksa pembayaran harian dan pengiriman produk setiap jam
        $schedule->command('periksa:bayardonasi')->hourly(); 
        $schedule->command('kirimproduk:lacak')->hourly(); 

        #kirim pengingat harian
        $schedule->command('pengingat:harian')->daily();
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
