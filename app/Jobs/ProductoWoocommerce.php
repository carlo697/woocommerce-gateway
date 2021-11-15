<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class ProductoWoocommerce implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
     public function middleware()
     {
         return [(new WithoutOverlapping("ProductoWoocommerce"))->dontRelease()];
     }
    public function handle()
    {
        error_log("holaa");
        $productos = Product::where('status', 'to_process')->with('productStore')->limit(20)->get();
        $divisiones = 5;
        $cantidad = $productos->count();
        error_log($cantidad);

        for ($i = 0; $i < $cantidad; $i += $divisiones) {
            $indice = $i;
            $array_respuesta = collect([]);

            for ($j = 0; $j < $divisiones; $j++) {
                if ($indice < $cantidad) {
                    // array_push($array_respuesta, $this->actualizar_woo($productos[$indice]));
                    $producto = $productos[$indice];
                    $body = $this->actualizar_woo($producto);

                    $array_respuesta->add(["producto" => $producto, "body" => $body]);
                    $indice++;
                } else {
                    break;
                }
            }

            $this->test($array_respuesta);
        }

    }

    public function test(Collection $test)
    {
        $cantidad = count($test);
        $primero = $test[0]["body"]['sku'];
        $ultimo = $test[$cantidad - 1]["body"]['sku'];
        Log::debug("Actualizando $cantidad productos. Comienza {$primero}. Termina {$ultimo}");

        $responses = Http::pool(fn(Pool $pool) => $test->map(
            function ($item) use ($pool) {
                $id = $item["body"]["id"];

                return $pool->withHeaders([
                    'consumer_key' => 'ck_fd6c1a59e0aa18902ff0aa3739b928285954f846',
                    'consumer_secret' => 'cs_a345f84f9e90c71feeaca7aa2b443060bb57f3d0',
                ])->post("https://redvital.com/dev1/wp-json/wc/v3/products/$id", $item["body"]);
            })->all());

        error_log("Respuestas recibidas...");

        for ($i = 0; $i < $test->count(); $i++) {
            $producto = $test[$i]["producto"];
            $response = $responses[$i];

            Log::debug("Producto: " . $producto["sku"] . ", actualizando, con estado: " . ($response->ok() ? "OK" : "FAIL"));
            $estadoNuevo = $response->ok() ? "success" : "failed";
            $producto->update(["status" => $estadoNuevo]);
        }

    }
    public function actualizar_woo($producto)
    {

        $resultado = DB::connection("woocommerce")->table('wplp_postmeta')->where('meta_value', $producto->sku)->first();

        $respuesta = [];
        $respuesta['id'] = $resultado ? $resultado->post_id : "";
        $respuesta['sku'] = $producto->sku;

        $respuesta["sale_price"] = strval($producto->sale_price);

        $respuesta["regular_price"] = strval($producto->regular_price);

        $respuesta["meta_data"] = [];

        foreach ($producto->ProductStore as $location) {

            $idTienda = $this->locations_id[$location->store_id];

            $key = "wcmlim_stock_at_$idTienda";
            $value = $location->stock;
            $data = ["key" => $key, "value" => $value];
            array_push($respuesta["meta_data"], $data);

            $key = "wcmlim_sale_price_at_$idTienda";
            $value = $location->sale_price;
            $data = ["key" => $key, "value" => $value];
            array_push($respuesta["meta_data"], $data);

            $key = "wcmlim_regular_price_at_$idTienda";
            $value = $location->regular_price;
            $data = ["key" => $key, "value" => $value];
            array_push($respuesta["meta_data"], $data);

        }
        return $respuesta;

    }

    public $locations_id = [
        // "Barquisimeto" => 4075,
        // "Kervis_pruebas" => 4085,
        // "Turmero" => 4077,
        // "Valencia" => 4073,

        2 => 4075,
        "Kervis_pruebas" => 4085,
        3 => 4077,

        1 => 4073,
    ];
}
