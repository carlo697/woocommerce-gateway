<?php

namespace App\Models;

use App\Models\WooOrder;
use App\Models\WooOrderProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WooOrderItemMeta extends Model
{
    use HasFactory;
    protected $connection = 'woocommerce';
    protected $table = 'wplp_woocommerce_order_itemmeta';

    public function order()
    {
        return $this->belongsTo(WooOrder::class, 'order_id', 'order_id');
    }

    public function orderProduct()
    {
        return $this->belongsTo(WooOrderProduct::class, 'order_item_id', 'order_item_id');
    }
}
