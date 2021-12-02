<?php

namespace App\Http\Controllers;

use App\Jobs\ProductsFileJob;
use App\Models\FileProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileProductController extends Controller
{

    private $validation_rules = [
        'data' => 'required',

    ];
    public function index()
    {
        return $this->showAll(FileProduct::all());
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), $this->validation_rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        $data = collect($request->data);
        $sizeObjet = sizeof($data);

        $objetos = collect([]);
        $chunkSize = 5000;

        for ($i = 0; $i < $sizeObjet; $i += $chunkSize) {
            $content = $data->splice(0, $chunkSize);
            $dataString = json_encode($content);
            $fileName = Str::uuid() . "-productos.txt";
            $file = Storage::disk('local')->put($fileName, $dataString);

            $file = [
                "file" => $fileName,
                "status" => "pending",
            ];
            $fileProduct = FileProduct::create($file);
            // $objetoCreado = new FileProductsResource($fileProduct);

            $objetos->add($fileProduct);

            ProductsFileJob::dispatch($fileProduct);

        }

        return $this->showAll($objetos);

    }

    public function show(FileProduct $fileProduct)
    {

        return $this->showOne($fileProduct);
    }

    public function prueba()
    {
        $respuesta = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode('ck_5c29b967481631bec7f2cb9a427e255877955bf6:cs_ee563f1678a6b0323fa466b70de2197e047dbef8'),

        ])->post("https://redvital.com/dev1/wp-json/wc/v3/products", array(
            'status' => 'draft',
            'id' => '',
            'name' => 'productos_prueba_gateway20',
            'sku' => '2020',
            'sale_price' => '11',
            'regular_price' => '12',
            'meta_data' => array(
                0 => array(
                    'key' => 'wcmlim_stock_at_4073',
                    'value' => '5',
                ),
                1 => array(
                    'key' => 'wcmlim_sale_price_at_4073',
                    'value' => '12',
                ),
                2 => array(
                    'key' => 'wcmlim_regular_price_at_4073',
                    'value' => '13',
                ),
                3 => array(
                    'key' => 'wcmlim_stock_at_4075',
                    'value' => '4',
                ),
                4 => array(
                    'key' => 'wcmlim_sale_price_at_4075',
                    'value' => '10',
                ),
                5 => array(
                    'key' => 'wcmlim_regular_price_at_4075',
                    'value' => '13',
                ),
            ),
        ));
        error_log($respuesta->ok());
        error_log($respuesta->successful());
        error_log($respuesta->failed());
        return $respuesta->body();
    }
}
