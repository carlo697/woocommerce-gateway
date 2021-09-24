<?php

namespace App\Models;

use App\Models\WooOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WooOrderProduct extends Model
{
    use HasFactory;
    protected $connection = 'woocommerce';
    protected $table = 'wp_wc_order_product_lookup';

    public function order()
    {
        return $this->belongsTo(WooOrder::class, 'order_id', 'order_id');
    }

}
