<?php

namespace App\Jobs;

use App\Models\FileProduct;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductsFileJob implements ShouldQueue
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

        // validar si existe ProductFile sin Procesar

        $file = FileProduct::where('status', '=', 'pending')->first();
        if (!$file) {
            Log::debug("No hay archivo para ser procesado");
        }
        $productsFile = $file->file;
        $contentFile = Storage::disk('local')->get($productsFile);
        $productos = json_decode($contentFile, true);
        $contador = 0;
        foreach ($productos as $producto) {
            $rule = [
                "sku" => "required",
            ];
            $validator = Validator::make($producto, $rule);
            if ($validator->fails()) {
                continue;
            }
            $productInfo = [
                "sku" => $producto['sku'],
                "name" => $producto['name'],
                "sale_price" => $producto['sale_price'],
                "regular_price" => $producto['regular_price'],
            ];
            $resultado = Product::where('sku', $producto['sku'])->first();

            if (!$resultado) {
                error_log("no existe sku " . $producto["sku"] . " se creara un nuevo producto");
                Product::create($productInfo);
                continue;
            }
            $resultado->update($productInfo);
            error_log("producto actualizado ".$producto["sku"]);

        }
        /* for($i = 0 ; $i <= sizeof($json); $i++)
        {

        $product::create($this->fakeIfoProduct);
        $contador++;
        if ($contador  > 20) {
        $contador = 0;
        error_log("el valor es : $i");
        }

        } */

        // $this->validate($request, $this->rules);
        // $resultado = DB::connection("woocommerce")->table('wplp_postmeta')->where('meta_value', $sku)->first();
        // if (!$resultado) {
        //     return $this->errorResponse("No existe un producto con el codigo SKU $sku", Response::HTTP_NOT_FOUND);
        // }
        // $id = $resultado->post_id;

        // $data = $request->all();

        // $respuesta = [];

        // if ($request->sale_price) {
        //     $respuesta["sale_price"] = strval($request->sale_price);
        // }

        // if ($request->regular_price) {
        //     $respuesta["regular_price"] = strval($request->regular_price);
        // }

        // if ($request->locations) {
        //     $respuesta["meta_data"] = [];

        //     foreach ($request->locations as $location => $values) {
        //         if (is_array($values)) {

        //             if (isset($values["stock"]) && is_numeric($values["stock"])) {
        //                 $idTienda = $this->locations_id[$location];
        //                 $key = "wcmlim_stock_at_$idTienda";
        //                 $value = $values["stock"];

        //                 $data = ["key" => $key, "value" => $value];
        //                 array_push($respuesta["meta_data"], $data);
        //             }

        //             if (isset($values["sale_price"]) && is_numeric($values["sale_price"])) {
        //                 $idTienda = $this->locations_id[$location];
        //                 $key = "wcmlim_sale_price_at_$idTienda";
        //                 $value = $values["sale_price"];

        //                 $data = ["key" => $key, "value" => $value];
        //                 array_push($respuesta["meta_data"], $data);
        //             }

        //             if (isset($values["regular_price"]) && is_numeric($values["regular_price"])) {
        //                 $idTienda = $this->locations_id[$location];
        //                 $key = "wcmlim_regular_price_at_$idTienda";
        //                 $value = $values["regular_price"];

        //                 $data = ["key" => $key, "value" => $value];
        //                 array_push($respuesta["meta_data"], $data);
        //             }
        //         }

        //         // if (isset($location["location"])) {
        //         //     $respuesta["meta_data"] = $location["location"];
        //         // }
        //     }
        // }

        // $response = Http::withHeaders([
        //     'consumer_key' => 'ck_fd6c1a59e0aa18902ff0aa3739b928285954f846',
        //     'consumer_secret' => 'cs_a345f84f9e90c71feeaca7aa2b443060bb57f3d0',

        // ])->post("https://redvital.com/dev1/wp-json/wc/v3/products/$id", $respuesta);

        // return response()->json(json_decode($response));
    }
}
