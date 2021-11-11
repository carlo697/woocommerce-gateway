<?php

namespace App\Models;

use App\Models\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductStore extends Model
{
    use HasFactory;
    protected $table = "product_stores";
    protected $fillable = ['sku', 'store_id', 'stock','sale_price', 'regular_price', 'status', 'error'];


    public function tiendas()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
    public function producto()
    {
        return $this->belongsTo(Product::class, 'sku');
    }

}
