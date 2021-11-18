@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Producto</th>
                                <th scope="col">Tiendas</th>
                                <th scope="col">stock</th>
                                <th scope="col">sale price</th>
                                <th scope="col">Regular Price</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($productTiendas as $key)
                            <tr>


                                <td scope="row">
                                    SKU : {{$key->sku}},
                                    <br>
                                    Nombre : {{$key->name}}
                                </td>
                                <td>
                                    @foreach($key->productStore as $valor)
                                        {{$valor->tiendas->name}}
                                        <hr>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($key->productStore as $valor)
                                        {{$valor->stock}}
                                        <hr>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($key->productStore as $valor)
                                        {{$valor->sale_price}}
                                        <hr>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($key->productStore as $valor)
                                        {{$valor->regular_price}}
                                        <hr>
                                    @endforeach
                                </td>


                                

                            </tr>
                            @endforeach

                        </tbody>
                    </table>









                </div>
            </div>
        </div>
    </div>
</div>
@endsection