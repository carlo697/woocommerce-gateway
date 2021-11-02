<?php

namespace App\Models;

use App\Models\WooCustomer;
use App\Models\WooOrderProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WooOrder extends Model
{
    use HasFactory;
    protected $connection = 'woocommerce';
    protected $table = 'wplp_wc_order_stats';

    public function products()
    {
        return $this->hasMany(WooOrderProduct::class, 'order_id', 'order_id');
    }
    
    public function customer()
    {
        return $this->belongsTo(WooCustomer::class, 'customer_id', 'customer_id');
    }
}
