<?php

namespace App\Console;

use App\Console\Commands\Gateway;
use App\Console\Commands\ProcessingProduct;
use App\Jobs\ProductoWoocommerce;
use App\Jobs\ProductsFileJob;
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
        Gateway::class,
        ProcessingProduct::class,

    ];

    protected function schedule(Schedule $schedule)
    {
        /* $schedule->command('inspire')->hourly();
        $schedule->call(function () {
        })->everyMinute()->name("someName")->withoutOverlapping(); */
        // $schedule->command('gateway:start')->everyMinute()->name("someName")->withoutOverlapping();

/*           $schedule->call(function () {
ProductsFileJob::dispatch();
})->name("Product File")->withoutOverlapping();
$schedule->call(function () {
ProductoWoocommerce::dispatch();
})->name("Product Woocommerce")->withoutOverlapping();  */
        $schedule->job(new ProductoWoocommerce)->everyMinute();
        $schedule->job(new ProductsFileJob)->everyMinute();

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
