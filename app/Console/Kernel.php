<?php

namespace App\Console;

use App\Models\Order;
use App\Jobs\ProductsFileJob;
use App\Console\Commands\Gateway;
use App\Jobs\ProductoWoocommerce;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Console\Commands\ProcessingProduct;
use Illuminate\Console\Scheduling\Schedule;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Gateway::class,
        ProcessingProduct::class,

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
        // $schedule->call(function () {

        
        // })->everyMinute()->name("someName")->withoutOverlapping();
        // $schedule->command('gateway:start')->everyMinute()->name("someName")->withoutOverlapping();
        // $schedule->job(new ProductsFileJob)->everyMinute();
        //
        //  $schedule->call(function () {
        //     ProductsFileJob::dispatch();
        // })->name("Product File")->withoutOverlapping();
        $schedule->call(function () {
            ProductoWoocommerce::dispatch();
        })->name("Product Woocommerce")->withoutOverlapping(); 
        // $schedule->job(new Heartbeat)->everyFiveMinutes();
        
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


// finish en el programa 