@extends('layouts.app')
<script src="{{ asset('js/app.js') }}" defer></script>
@section('content')
    <div class="container">
        @include('includes.barraMenu')

        <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Lista de convenios</h1>
        <br>

        <div class="shadow-lg card border-primary" style="border-radius: 20px; margin: 15px;">
            <div class="card-body">
                <gestion-convenio-show id_c="{{$data['id_cliente']}}"/>
                <div style="margin: 30px;">
                    <a href="#" onclick="history.go(-1)" style="font-size: 12px; border-radius: 20px;"
                       class="btn btn-outline-secondary btn-block">Regresar</a>
                </div>
            </div>
        </div>
    </div>
@endsection




