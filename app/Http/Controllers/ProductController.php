<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

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

    public function update(Request $request, $sku)
    {
        $this->validate($request, $this->rules);

        $resultado = DB::connection("woocommerce")->table('wplp_postmeta')->where('meta_value', $sku)->first();
        if (!$resultado) {
            return $this->errorResponse("No existe un producto con el codigo SKU $sku", Response::HTTP_NOT_FOUND);
        }
        $id = $resultado->post_id;
        
        $data = $request->all();

        $respuesta = [];

        if ($request->sale_price) {
            $respuesta["sale_price"] = strval($request->sale_price);
        }

        if ($request->regular_price) {
            $respuesta["regular_price"] = strval($request->regular_price);
        }

        if ($request->locations) {
            $respuesta["meta_data"] = [];

            foreach ($request->locations as $location => $values) {
                if (is_array($values)) {

                    if (isset($values["stock"]) && is_numeric($values["stock"])) {
                        $idTienda = $this->locations_id[$location];
                        $key = "wcmlim_stock_at_$idTienda";
                        $value = $values["stock"];

                        $data = ["key" => $key, "value" => $value];
                        array_push($respuesta["meta_data"], $data);
                    }

                    if (isset($values["sale_price"]) && is_numeric($values["sale_price"])) {
                        $idTienda = $this->locations_id[$location];
                        $key = "wcmlim_sale_price_at_$idTienda";
                        $value = $values["sale_price"];

                        $data = ["key" => $key, "value" => $value];
                        array_push($respuesta["meta_data"], $data);
                    }

                    if (isset($values["regular_price"]) && is_numeric($values["regular_price"])) {
                        $idTienda = $this->locations_id[$location];
                        $key = "wcmlim_regular_price_at_$idTienda";
                        $value = $values["regular_price"];

                        $data = ["key" => $key, "value" => $value];
                        array_push($respuesta["meta_data"], $data);
                    }
                }

                // if (isset($location["location"])) {
                //     $respuesta["meta_data"] = $location["location"];
                // }
            }
        }

        $response = Http::withHeaders([
            'consumer_key' => 'ck_fd6c1a59e0aa18902ff0aa3739b928285954f846',
            'consumer_secret' => 'cs_a345f84f9e90c71feeaca7aa2b443060bb57f3d0',

        ])->post("https://redvital.com/dev1/wp-json/wc/v3/products/$id", $respuesta);
        

        return response()->json(json_decode($response));
    }
}
