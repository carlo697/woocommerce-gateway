<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
     
    
    public function find($array, $func)
    {
        foreach ($array as $item) {
            if ($func($item)) {
                return $item;
            }
        }
    
        return null;
    }
    public function toArray($request)
    {

        $result = [
            'id' => $this->id,
            'status' => $this->status,
            'products' => OrderProductResource::collection($this->line_items),
            'discount_total' => $this->discount_total,
            'discount_tax' => $this->discount_tax,
            'shipping_total' => $this->shipping_total,
            'shipping_tax' => $this->shipping_tax,
            'cart_tax' => $this->cart_tax,
            'total' => $this->total,
            'total_tax' => $this->total_tax,
            'billing' => $this->billing,
            'date_completed' => $this->date_completed,
            'date_paid' => $this->date_paid
        ];

        $result["billing"]->ci = $this->find($this->meta_data, function ($item) {
            return $item->key === '_billing_ci';
        })?->value;
        
        $result["currency"] = $this->currency;
        $result["currency_symbol"] = $this->currency_symbol;

        $paymentReference = $this->find($this->meta_data, function ($item) {
            return $item->key === 'woocommerce_customized_payment_data';
        })?->value?->data;
        
        if ($paymentReference) {
            $pago = [];
            
            if ($this->payment_method_title === "Transferencia bancaria") {
                $pago["method"] = "transferencia";
                $pago["bank"] = $paymentReference[0]->{"Bancos"};
                $pago["reference"] = $paymentReference[1]->{"Numero referencia"};
                $pago["total"] = $paymentReference[2]->{"Monto"};
            } else if ($this->payment_method_title === "Pago Móvil") {
                $pago["method"] = "pago_movil";
                $pago["reference"] = $paymentReference[0]->{"Numero referencia"};
                $pago["total"] = $paymentReference[1]->{"Monto"};
            } else if ($this->payment_method_title === "Zelle") {
                $pago["method"] = "zelle";
                $pago["reference"] = $paymentReference[0]->{"Numero referencia"};
                $pago["holder_name"] = $paymentReference[1]->{"Nombre del Titular"};
                $pago["total"] = $paymentReference[2]->{"Monto"};
            } else {
                $pago["method"] = "unknown";
                $pago["data"] = $paymentReference;
            }
            
            $result["payment_info"] = $pago;
        }

        return $result;
    }
}
