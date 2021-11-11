<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'sku';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['sku','name', 'sale_price','regular_price'];

    
    public function productStore(){
        return $this->HasMany(ProductStore::class, 'sku');
    }



    
}
