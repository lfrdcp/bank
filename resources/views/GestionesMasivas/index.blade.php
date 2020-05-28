@extends('layouts.appSinJava')
@section('title','Gestiones másivas')
@section('content')
    <div class="container">
        @include('includes.barraMenu')


        <div class="card border-0" style="border-radius: 20px; margin: 15px;">
            <div class="card-title">
                <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Gestiones masivas</h1>
            </div>
        </div>

        <div class="card-columns">
            <div class="card border-0">
                <img src="{{asset('imagesTecmor/reporte_llamada.png')}}" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">Avance de campaña</h5>
                    <p class="card-text">Vaciar al sistema web las gestiones almacenadas en un CSV o XLSX, las cuales
                        fueron
                        realizadas de manera automática.</p>
                    <a href="/reporte_llamada" class="btn btn-primary">Acceder</a>
                </div>
            </div>
            <div class="card border-0">
                <img src="{{asset('imagesTecmor/enviada.png')}}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Otras gestiones</h5>
                    <p class="card-text">Vaciar al sistema web las gestiones almacenadas en un CSV o XLSX, las cuales
                        fueron
                        realizadas de manera manual.</p>
                    <a href="/otras_gestiones" class="btn btn-primary">Acceder</a>
                </div>
            </div>
            <div class="card border-0">
                <img src="{{asset('imagesTecmor/recibida.png')}}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Requerimientos</h5>
                    <p class="card-text">.</p>
                    <a href="#" class="btn btn-primary">Acceder</a>
                </div>
            </div>
        </div>

    </div>
@endsection
