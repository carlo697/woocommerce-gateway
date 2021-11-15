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

        // $schedule->call(function () {
        // })->everyMinute()->name("someName")->withoutOverlapping();
        //

        // $schedule->call(function () {
        //     ProductsFileJob::dispatch();
        // })->name("Product File")->withoutOverlapping();
        // $schedule->call(function () {
        //     ProductoWoocommerce::dispatch();
        // })->name("Product Woocommerce");
        $schedule->command('gateway:start')->everyMinute()->name("someName")->withoutOverlapping();
        $schedule->job(new ProductoWoocommerce("ProductoWoocommerce"))->everyMinute()->name("ProductoWoocommerce")->withoutOverlapping();
        $schedule->job(new ProductsFileJob("ProductsFileJob"))->everyMinute()->name("ProductsFileJob")->withoutOverlapping();

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
