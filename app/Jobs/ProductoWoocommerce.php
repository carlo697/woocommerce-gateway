<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
    public function handle()
    {
        $productos = Product::where('status', 'to_process')->with('productStore')->limit(20)->get();

        // error_log($producto);

        $this->actualizar_woo($productos);
    }
    public function actualizar_woo($productos)
    {
        foreach ($productos as $producto) {
             $resultado = DB::connection("woocommerce")->table('wplp_postmeta')->where('meta_value', $producto->sku)->first();
             if (!$resultado) {
                 $producto->status = 'failed';
                 $producto->save();
                 // return $this->errorResponse("No existe un producto con el codigo SKU $producto->sku", Response::HTTP_NOT_FOUND);
                 return;
            }
            $id = $resultado->post_id;

            $id = $producto->id;

            $respuesta = [];

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

            $response = Http::withHeaders([
                'consumer_key' => 'ck_fd6c1a59e0aa18902ff0aa3739b928285954f846',
                'consumer_secret' => 'cs_a345f84f9e90c71feeaca7aa2b443060bb57f3d0',
            ])->post("https://redvital.com/dev1/wp-json/wc/v3/products/$id", $respuesta);
            Log::debug(response()->json(json_decode($response)));

        }

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
