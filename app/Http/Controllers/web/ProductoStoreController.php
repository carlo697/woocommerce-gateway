<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductoStoreController extends Controller
{
    public function index(Request $requets)
    {
        $query = $requets->get('query');
        $products = new Product;
        if ($query) {
            $tiendas = $products->whereHas('productStore', function (Builder $builder) use ($query) {
                $builder->where('store_id', $query);
            })->with(['productStore' => function ($builder) use ($query) {

                $builder->where('store_id', $query);

            }])->get();
            return $tiendas;
        }

        return $products->with('productStore')->get();

        return view('home');
    }
}
