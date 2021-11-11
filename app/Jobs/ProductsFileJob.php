<?php

namespace App\Jobs;

use App\Models\FileProduct;
use App\Models\Product;
use App\Models\ProductStore;
use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
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
        try {
            $file = FileProduct::where('status', '=', 'pending')->orWhere('status', '=', 'processing')->first();
            if (!$file) {
                Log::debug("No hay archivo para ser procesado");
                return "No hay archivo para ser procesado";
            }
            // buscar archivos para leer información
            $productsFile = $file->file;
            if (empty($productsFile)) {
                Log::debug("no hay archivo");
                return;
            }
            $file->status = 'processing';
            $file->save();
            $contentFile = Storage::disk('local')->get($productsFile);
            $this->productos($contentFile, $file);
            $file->status = 'done';
            return $file->save();
        } catch (\Exception$e) {
            error_log($e);

        }
    }

    public function productos(String $contenido)
    {

        $productos = json_decode($contenido, true);
        $timeInit = Carbon::now();
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
                "status" => "to_process"
            ];
            $resultado = Product::where('sku', $producto['sku'])->first();
            $diff = $timeInit->diffInSeconds(Carbon::now());

            if (!$resultado) {
                Product::create($productInfo);
                $this->ProductStore($producto);

                continue;
            }
            error_log("actualizado");
            $resultado->update($productInfo);
            $this->ProductStore($producto);
            error_log("se actualizo producto con sku " . $producto['sku']);

        }

        return "completado los productos";

    }

    public function ProductStore($producto)
    {

        if ($producto['location']) {
            $sizeLocattion = $producto['location'];
            foreach ($sizeLocattion as $valor => $key) {
                $productStora = ProductStore::where("sku", $producto['sku'])->Where('store_id', $valor)->first();
                $store = Store::where('id', $valor)->first();
                if (!$store) {
                    continue;
                }
                $data = [
                    "sku" => $producto["sku"],
                    "store_id" => $valor,
                    "stock" => $key['stock'],
                    "regular_price" => $key['regular_price'],
                    "sale_price" => $key['sale_price'],
                    
                ];
                if (!$productStora) {
                    ProductStore::create($data);
                    error_log("creado productStore");
                    continue;
                }
                $productStora->update($data);
                error_log("actualizado productStore");

            }
        }
    }

}
