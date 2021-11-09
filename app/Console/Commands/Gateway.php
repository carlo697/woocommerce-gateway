<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class Gateway extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gateway:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $ordenes = Order::where("is_processed", false)->get();

        foreach ($ordenes as $orden) {
            // Intentar informar al inventario de la compra que se hizo en el e-commerce
            // EL catch se ejecutara en caso de que se supere el timeout
            try {
                $response = Http::withHeaders([
                    'Authorization' => env('KEY_SECRET_INVENTARIO'),
                ])->timeout(30)->post('https://gateway.redvital.com/public/api/fake_orders', [
                    'data' => $orden->processed_resource,
                ]);
                Log::debug("Respuesta: {$response->status()}");
                // Si no se encuentra el servidor, se saltaran todos los request
                if ($response->status() === Response::HTTP_NOT_FOUND) {
                    Log::debug("Se perdio la conexión con el servidor del inventario");
                    return;
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
                Log::error("Se perdio la conexión con el servidor del inventario");
                Log::error($th);
                return;
            }
        }
    }
}
