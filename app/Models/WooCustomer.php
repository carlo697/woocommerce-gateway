<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WooCustomer extends Model
{
    use HasFactory;
    protected  $connection = 'woocommerce';
    protected $table = 'wp_wc_customer_lookup';
}
