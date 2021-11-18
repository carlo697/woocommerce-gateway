<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStore;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
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

    private $rules = [

        "sale_price" => "numeric",
        "regular_price" => "numeric",
        "locations" => "array",
    ];

    private $fakeIfoProduct = [
        "name" => "fakeProduct",
        "sale_price" => 1,
        "regular_price" => 1,
    ];

    public function show($sku)
    {
        $product = Product::where('sku', $sku)->with('productStore')->first();
        return $product;
        return $this->actualizar_woo($product);

    }

    public function update(Request $request, Product $sku)
    {
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return "falla la validacion";
        }

        $productInfo = [
            "sku" => $request['sku'],
            "name" => $request['name'],
            "sale_price" => $request['sale_price'],
            "regular_price" => $request['regular_price'],
        ];
        $resultado = Product::where('sku', $request['sku'])->first();

        if (!$resultado) {
            $newProduct = Product::create($productInfo);
            $this->ProductStore($request);
            error_log("no se a encontrado sku se creara " . $request['sku'] . " segundos transcurrido ");
            return;
        }
        $resultado->update($productInfo);
        $this->ProductStore($request);

        return "se crearon los productos";

    }
    public function ProductStore($request)
    {

        if ($request['location']) {
            $sizeLocattion = $request['location'];
            foreach ($sizeLocattion as $valor => $key) {
                $productStora = ProductStore::where("sku", $request['sku'])->Where('store_id', $valor)->first();
                $store = Store::where('id', $valor)->first();
                if (!$store) {
                    continue;
                }
                $data = [
                    "sku" => $request["sku"],
                    "store_id" => $valor,
                    "stock" => $key['stock'],
                    "regular_price" => $key['regular_price'],
                    "sale_price" => $key['sale_price'],
                    "status" => "to_process",
                ];
                if (!$productStora) {
                    ProductStore::create($data);
                    continue;
                }
                $productStora->update($data);

            }
        }
    }

    public function listProduct(Request $request)
    {
        $product = $request->all();
        $product = new Product();
        $contador = 0;
        for ($i = 0; $i < 20000; $i++) {
            $this->fakeIfoProduct["sku"] = $i;
            $product::create($this->fakeIfoProduct);

            $contador++;
            if ($contador > 20) {
                $contador = 0;
                error_log("el valor es : $i");
            }

        }

        return "se registraron los productos";
    }
}
