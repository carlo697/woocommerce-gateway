<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected  $connection = 'woocommerce';
    protected $table = 'wp_wc_order_stats';

    public function products(){
        return $this->hasMany(OrderProduct::class, null, 'order_id');
    }
}
