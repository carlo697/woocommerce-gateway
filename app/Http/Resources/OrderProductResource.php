<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use function App\Util\find;

class OrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
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
