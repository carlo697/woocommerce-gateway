<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;
    protected  $connection = 'woocommerce';
    protected $table = 'wp_wc_order_product_lookup';


    public function order(){
        return $this->belongsTo(Order::class, null, 'order_id' );
    }

    
}
