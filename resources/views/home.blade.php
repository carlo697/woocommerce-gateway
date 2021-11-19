@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form class="form-inline " action="{{ route('producto.search') }}">
                @csrf
                <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" name="q" placeholder="Search"
                        aria-label="Search" autocomplete="off">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <div class="card">
                <div class="card-header">Tiendas:
                    <a href="{{ route('products.index') }}" class="btn btn-sm  btn-primary">Todas las tiendas </a>
                    @foreach ( $tiendas as $tienda )
                    <a href="{{ route('products.index',['query' => $tienda->id]) }}"
                        class="btn btn-sm  btn-primary">{{$tienda->name}}</a>
                    @endforeach

                </div>

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
                    <div class="d-flex justify-content-center">
                        {!! $productTiendas->links() !!}
                    </div>










                </div>
            </div>
        </div>
    </div>
</div>
@endsection