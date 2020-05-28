@extends('layouts.app')
@section('title','Gestionar aval')
@section('content')
    <div class="container">
        @include('includes.barraMenu')

        <div class="card border-0" style="border-radius: 20px; margin: 15px;">
            <div class="card-title">
                <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Gestionar aval</h1>
            </div>
        </div>
        <div class="card border-0" style="border-radius: 20px; margin: 15px;">
            <div class="card-body">
                <agregar-datos-aval id_cliente="{{$data}}" tipo_usuario="{{auth()->user()->tipo}}"></agregar-datos-aval>
                <editar-nombre-aval></editar-nombre-aval>
                <editar-direccion-aval></editar-direccion-aval>
                <editar-telefono-aval></editar-telefono-aval>
                <agregar-telefono-aval></agregar-telefono-aval>
                <a href="/gestion/{{$data}}"
                   class="btn btn-secondary btn-block">Regresar</a>
            </div>
        </div>
@endsection
