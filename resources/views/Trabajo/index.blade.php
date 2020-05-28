@extends('layouts.app')
@section('title','Gestionar trabajo(s) del cliente')
@section('content')
    <div class="container">
        @include('includes.barraMenu')


        <div class="card border-0" style="border-radius: 20px; margin: 15px;">
            <div class="card-title">
                <h1 class="text-center text-secondary " style="font-size: 25px; font-family: 'Century Gothic'">Gestionar trabajo(s) del cliente</h1>
            </div>
        </div>
        <div class="card border-0" style="border-radius: 20px; margin: 15px;">
            <div class="card-body">
                <editar-direccion-trabajo></editar-direccion-trabajo>
                <agregar-direccion-trabajo id_cliente="{{$data}}" tipo_usuario="{{auth()->user()->tipo}}"></agregar-direccion-trabajo>
                <a href="/gestion/{{$data}}"
                   class="btn btn-secondary btn-block">Regresar</a>
            </div>
        </div>

@endsection
