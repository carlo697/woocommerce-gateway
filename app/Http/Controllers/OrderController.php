<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

function find($array, $func)
{
    foreach ($array as $item) {
        if ($func($item)) {
            return $item;
        }
    }

    return null;
}

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'status' => $this->status,

            'discount_total' => $this->discount_total,
            'discount_tax' => $this->discount_tax,
            'shipping_total' => $this->shipping_total,
            'shipping_tax' => $this->shipping_tax,
            'cart_tax' => $this->cart_tax,
            'total' => $this->total,
            'total_tax' => $this->total_tax,
            'currency_symbol' =>  $this->currency_symbol,

            'shipping' => $this->shipping,

            'date_completed' => $this->date_completed,
            'date_paid' => $this->date_paid,

            'payment_method_title' => $this->payment_method_title,
            'payment_method' => $this->payment_method,

            // 'payment_method_title' => $this->payment_method_title,
            // 'payment_method_title' => $this->payment_method_title,
            // 'products' => $this->line_items,
            'products' => OrderProductResource::collection($this->line_items),
        ];

        $result["shipping"]->ci = find($this->meta_data, function ($item) {
            return $item->key === '_billing_ci';
        })?->value;

        $result["payment_reference"] = find($this->meta_data, function ($item) {
            return $item->key === 'woocommerce_customized_payment_data';
        })?->value?->data;

        return $result;
    }
}

class OrderProductResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'quantity' => $this->quantity,
            'sku' => $this->sku,
            'price' => $this->price,

            'subtotal' => $this->subtotal,
            'subtotal_tax' => $this->subtotal_tax,
            'total' => $this->total,
            'total_tax' => $this->total_tax,
            "taxes" => $this->taxes,
        ];

        $result["location"] = find($this->meta_data, function ($item) {
            return $item->key === 'Location';
        })?->value;

        return $result;
    }
}

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
        Log::error("*______________________________desde la consola ________________________________________________________________*/");
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
        Log::error("Procesada orden en el inventario!!!");

        // throw new AuthorizationException("hola");
        Log::error("Devolver respuesta!!");
        return "ok";
    }
}
