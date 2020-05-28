@extends('layouts.app')
@section('title','Pago intención')
@section('content')
    <div class="container">
        @include('includes.barraMenu')

        <div class="card border-0" style="border-radius: 20px; margin: 15px;">
            <div class="card-title">
                <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Pago intención</h1>
            </div>
        </div>


        <div class="card border-0" style="border-radius: 20px; margin: 15px;">
            <div class="card-body">
                <pago-intencion id_c="{{$data}}"></pago-intencion>

                <a href="/gestion/{{$data}}"
                   class="btn btn-secondary btn-block">Regresar</a>
            </div>
        </div>


@endsection
