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
        "Barquisimeto" => 4075,
        "Kervis_pruebas" => 4085,
        "Turmero" => 4077,
        "Valencia" => 4073,
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

    public function show(Product $sku)
    {
        return $this->showOne($sku);
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
    public function ProductStore(Request $request)
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

    public function delete()
    {}

    public function actualizar_woo(Request $request)
    {
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
