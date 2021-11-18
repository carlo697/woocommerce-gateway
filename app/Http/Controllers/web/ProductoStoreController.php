<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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
            // return $productTiendas;
        } else {
            $productTiendas = $products->with('productStore')->get();
        }

        $tiendas = Store::all();
        $var =  response()->json($productTiendas);
        return view('home', compact('tiendas', 'productTiendas'));
    }
}
