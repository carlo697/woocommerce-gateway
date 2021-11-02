<?php

namespace App\Http\Resources;

use function App\Util\find;
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

            'discount_total' => $this->discount_total,
            'discount_tax' => $this->discount_tax,
            'shipping_total' => $this->shipping_total,
            'shipping_tax' => $this->shipping_tax,
            'cart_tax' => $this->cart_tax,
            'total' => $this->total,
            'total_tax' => $this->total_tax,
            'currency_symbol' => $this->currency_symbol,

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
