<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStore extends Model
{
    use HasFactory;
    protected $table = "product_stores";
    protected $fillable = ['sku', 'store_id', 'stock','sale_price', 'regular_price', 'status', 'error'];
}
