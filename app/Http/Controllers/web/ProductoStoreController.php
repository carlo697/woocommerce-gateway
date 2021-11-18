<?php

namespace App\Http\Controllers\web;

use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class ProductoStoreController extends Controller
{
    public function index(Request $requets)
    {
        $query = $requets->get('query');
        $products = new Product;
        if ($query) {
            $productTiendas = $products->whereHas('productStore', function (Builder $builder) use ($query) {
                $builder->where('store_id', $query);
            })->with(['productStore' => function ($builder) use ($query) {

                $builder->where('store_id', $query);

            }])->get();
            
        }
        else{
            $productTiendas = $products->with('productStore')->get();
        }

        $tiendas = Store::all();

        return view('home',compact('tiendas', 'productTiendas'));
    }
}
