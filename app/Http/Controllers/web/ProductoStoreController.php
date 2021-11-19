<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ProductoStoreController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $requets)
    {
        $query = $requets->get('query');
        $products = new Product;
        if ($query) {
            $productTiendas = $products->whereHas('productStore', function (Builder $builder) use ($query) {
                $builder->where('store_id', $query);
            })->with(['productStore' => function ($builder) use ($query) {

                $builder->where('store_id', $query);

            }])->simplePaginate(10);
            // return $productTiendas;
        } else {
            $productTiendas = $products->with('productStore')->simplePaginate(10);
        }

        $tiendas = Store::simplePaginate(10);

        return view('home', compact('tiendas', 'productTiendas'));
        // return View::make('home')->with(compact($tiendas))->with(compact($productTiendas));
    }

    public function search(Request $request)
    {

        $q = $request->get('q');

        // get the products
        $productos = Product::where('sku', 'LIKE', '%' . $q . '%')
            ->orWhere('name', 'LIKE', '%' . $q . '%')->with('productStore')
            ->simplePaginate(10);

        // Render the view
        // return response()->json($productos);

        return view('search', compact('productos', 'q'));
    }
}
