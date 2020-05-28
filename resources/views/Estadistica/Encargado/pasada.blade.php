@extends('layouts.app')
@section('title','Estadistica')
@section('content')

    <div class="container">
        @include('includes.barraMenu')

        <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Gráfica de avance por encargado</h1>
        <div class="card border-0" style="border-radius: 20px;">
            <div class="card-body">
                <grafica-encargado-pasado fecha="{{$data}}"></grafica-encargado-pasado>
                <div style="margin: 30px;">
                    <a href="/estadistica"
                       class="btn btn-secondary btn-block">Regresar</a>
                </div>
            </div>
        </div>
    </div>
@endsection



