<?php

namespace App\Http\Controllers;

use App\Models\FileProduct;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\ProductsFileJob;
use Illuminate\Http\Response;
use App\Jobs\ProductoWoocommerce;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
}
