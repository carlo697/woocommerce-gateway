<?php

namespace App\Models;

use App\Models\WooOrder;
use App\Models\WooOrderItemMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WooOrderProduct extends Model
{
    use HasFactory;
    protected $connection = 'woocommerce';
    protected $table = 'wplp_wc_order_product_lookup';

    public function order()
    {
        return $this->belongsTo(WooOrder::class, 'order_id', 'order_id');
    }

    public function metas()
    {
        return $this->hasMany(WooOrderItemMeta::class, 'order_item_id', 'order_item_id');
    }
}
