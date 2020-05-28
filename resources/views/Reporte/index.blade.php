@extends('layouts.app')
@section('title','Reportes')
@section('content')
    <div class="container">
        @include('includes.barraMenu')

        <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Reportes</h1>
        <div class="card border-0">
            <div class="card-body">
                <div class="row">

                        <reporte-convenio-liquidacion></reporte-convenio-liquidacion>

                        <reporte-gestion></reporte-gestion>

                        <reporte></reporte>

                    <reporte-pago></reporte-pago>

                </div>
            </div>
        </div>
    </div>
@endsection
