<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected  $connection = 'woocommerce';
    protected $table = 'wp_wc_customer_lookup';
}
