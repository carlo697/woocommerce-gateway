<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function index()
    {
        $response = Http::get('https://redvital.com/dev1/wp-json/wc/v3/orders/35483', [
            'consumer_key' => 'ck_fd6c1a59e0aa18902ff0aa3739b928285954f846',
            'consumer_secret' => 'cs_a345f84f9e90c71feeaca7aa2b443060bb57f3d0',
        ]);

        $data = json_decode($response);
        // return $data;
        return new OrderResource($data);
        // return WooOrder::with("customer", "products")->get();
    }

    public function store(Request $request)
    {
        Log::error("*______________________________Orden de compra iniciadas_____________________________________________*/");
        $data = $request->all();

        // Ignorar las ordenes que no estén completadas
        if ($data["status"] != "completed") {
            Log::debug("La orden no está completada");
            return;
        }

        // Obtener la id de la orden
        $idOrden = $data["id"];

        // Ignorar si ya se registro la orden anteriormente
        $ordenEncontrada = Order::where("order_id", $idOrden)->first();
        if ($ordenEncontrada) {
            error_log("La orden ya se encuentra registrada");
            return;
        }

        $data = json_decode(json_encode($data));
        // Crear la orden nueva
        $orden = new Order();
        $orden->order_id = $idOrden;
        $orden->original_resource = $data;
        $orden->processed_resource = new OrderResource($data);
        $orden->save();

        Log::debug("!Orden registrada con exito!");

        // DB::connection("mysql")->insert('insert into data ( data) values (?)', [json_encode(["data" => $request->all()])]);
        return "ok";
    }

    public function fake_store(Request $request)
    {
        Log::error("Procesada orden en el inventario!!!");

        // throw new AuthorizationException("hola");
        Log::error("Devolver respuesta!!");
        return "ok";
    }
}
