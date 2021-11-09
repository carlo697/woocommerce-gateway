<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductStoreController extends Controller
{
    public function index(){}

    public function store(){}

    public function update(Request $request, ProductStoreController $sku){
        return "hola desde update";
    }

    public function delete(){}
}
