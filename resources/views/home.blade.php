@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    
                    @foreach ($productTiendas as $role)
                    <li>
                        <input type="checkbox" name="roles[]" value="{{ $role}}"> {{ $role }}
                    </li>
                    @endforeach

                    <br>
                    <br>

                    {{$tiendas}}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
