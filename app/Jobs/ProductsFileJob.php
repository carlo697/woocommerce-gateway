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
            $file->status = 'pending';
            $file->save();
        }
    }

    public function productos(String $contenido, $file)
    {

        $productos = json_decode($contenido, true);
        try {
            $contador = 0;
            foreach ($productos as $producto) {
                $contador++;
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
                    Product::create($productInfo);
                    continue;
                }
                $resultado->update($productInfo);
                if($contador >= 1000){
                    error_log($contador.'');
                    $contador=0;
                }

            }} catch (\Exception$e) {

            error_log($e);
        }

    }

}
