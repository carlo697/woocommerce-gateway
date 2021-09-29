<?php

namespace App\Console;

use App\Models\Order;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

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
        $schedule->call(function () {
            while (true) {
                $ordenes = Order::where("is_processed", false)->get();

                error_log(count($ordenes));

                foreach ($ordenes as $orden) {
                    // Intentar informar al inventario de la compra que se hizo en el e-commerce
                    // EL catch se ejecutara en caso de que se supere el timeout
                    try {
                        $response = Http::timeout(30)->post('https://bodegones.website/api/fake_orders', [
                            'data' => 'example',
                        ]);

                        error_log("Respuesta: {$response->status()}");

                        // Si no se encuentra el servidor, se saltaran todos los request
                        if ($response->status() === Response::HTTP_NOT_FOUND) {
                            error_log("Se perdio la conexión con el servidor del inventario");
                            break;
                        }

                        // Si el servidor devolvio un error, marcar la orden con un error
                        if ($response->failed()) {
                            error_log("Hubo un error");
                            $orden->returned_error = true;
                        }

                        // Marcar la orden como procesada
                        $orden->is_processed = true;
                        $orden->save();
                    } catch (\Throwable$th) {
                        // Se supero el timeout así que se saltaran todas las ordenes
                        error_log("Se perdio la conexión con el servidor del inventario");
                        error_log($th);
                        break;
                    }
                }
                
                sleep(1);
            }
        })->name("someName");
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
