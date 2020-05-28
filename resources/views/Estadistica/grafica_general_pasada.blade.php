@extends('layouts.app')
@section('title','Estadistica')
@section('content')

    <div class="container">
        @include('includes.barraMenu')

        <div class="card border-0" style="border-radius: 20px; margin: 15px;">
            <div class="card-title">
                <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Estadistica</h1>
                @if (session('msj'))
                    <input id="Idbienvenido" type="hidden" value="1">
                    <div class="alert alert-secondary" role="alert">
                        <h3>{{session('msj')}}</h3>
                    </div>
                @endif
            </div>
        </div>

        <div class="card border-0" style="border-radius: 20px;">
            <div class="card-body">
                <grafica-general-pasada meta="{{$data->monto}}" acumulado="{{$data->acumulado}}"></grafica-general-pasada>
                <br>

                <div style="margin: 30px;">
                    <a href="#" onclick="history.go(-1)"
                       class="btn btn-secondary btn-block">Regresar</a>
                </div>
            </div>
        </div>
    </div>
@endsection



