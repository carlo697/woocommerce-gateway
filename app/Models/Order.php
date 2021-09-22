<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected  $connection = 'woocommerce';
    protected $table = 'wp_wc_order_stats';

    public function products(){
        return $this->hasMany(OrderProduct::class, null, 'order_id');
    }
    public function customer(){
        return $this->belongsTo(Customer::class, null, 'customer_id' );
    }
}
