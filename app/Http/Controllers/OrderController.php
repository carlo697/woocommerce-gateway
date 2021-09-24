<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\WooOrder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return WooOrder::with("customer", "products")->get();
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // Ignorar las ordenes que no estén completadas
        if ($data["status"] != "completed") {
            error_log("La orden no está completada");
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

        // Crear la orden nueva
        $orden = new Order();
        $orden->order_id = $idOrden;
        $orden->save();

        error_log("!Orden registrada con exito!");

        // DB::connection("mysql")->insert('insert into data ( data) values (?)', [json_encode(["data" => $request->all()])]);
        return "ok";
    }

    public function fake_store(Request $request)
    {
        error_log("Procesada orden en el inventario!!!");
        sleep(20);
        // throw new AuthorizationException("hola");
        error_log("Devolver respuesta!!");
        return "ok";
    }
}
