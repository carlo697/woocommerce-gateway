<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrdenIndexResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $validacion = $request->completados;
        if (!$validacion) {
            $data = Order::where('invoiced', false)->get();

        } else {
            $data = Order::where('invoiced', true)->get();
        }

        return $this->showAllResources(OrdenIndexResource::collection($data));
        // // nueva api en woocommerc
        // $response = Http::get('https://redvital.com/dev1/wp-json/wc/v3/orders', [
        //     'consumer_key' => 'ck_b9af6fe468d7be24569f9d1c592e4d153352a7f0',
        //     'consumer_secret' => 'cs_564e5240175f31758f1b80a3f341744d842f42a9',
        // ]);

        // return $response;

        // return new OrderResource(json_decode($response));
    }

    public function show(Order $id)
    {
        return ["data" => $id->processed_resource];
    }

    public function store(Request $request)
    {
        Log::debug("__Orden de compra editada__");
        $data = $request->all();
        // // Ignorar las ordenes que no estén completadas
        //  if ($data["invoice_number"] != null) {
        //      Log::debug("La orden no está completada");
        //      return;
        //  }

        // Obtener la id de la orden
        $idOrden = $data["id"];

        //  Ignorar si ya se registro la orden anteriormente
        $ordenEncontrada = Order::where("order_id", $idOrden)->first();
        if ($ordenEncontrada) {
            Log::debug("La orden ya se encuentra registrada");
            return "orden de compra existe";
        }

        $data = json_encode($data);

        Log::debug($data);

        // Crear la orden nueva
        $orden = new Order();
        $orden->order_id = $idOrden;
        $orden->original_resource = $data;
        $orden->processed_resource = new OrderResource(json_decode($data));
        $orden->save();
        Log::debug("!Nueva orden registrada con exito!");
        return "ok";
    }

    public function update(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            "invoice_number" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $order->update($request->all());
        $order->invoiced = 1;
        $order->save();
        return $this->showOne($order);
    }

}
