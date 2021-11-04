<?php

namespace App\Http\Controllers;


use App\Models\FileProduct;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileProductController extends Controller
{

    private $validation_rules = [
        'data' => 'required',
        
    ];
    public function index()
    {
        return "hola desde index";
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), $this->validation_rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $data = $request->data;
        $dataString = json_encode($data);
        $fileProduct = new FileProduct();
        $fileName = Str::uuid()."-productos.txt";
        Storage::disk('local')->put($fileName, $dataString);
        $url =  Storage::url($fileName);
        $fileProduct->file = $url;
        $fileProduct->save();
        return $this->showOne($fileProduct,201);

    }

    public function show()
    {

        return "show";
    }
}
