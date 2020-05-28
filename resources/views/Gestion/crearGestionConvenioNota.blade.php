@extends('layouts.app')
@section('title','Crear gestión')
@section('content')
    <div class="container">
        @include('includes.barraMenu')

        <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Crear gestión</h1>

        <div class="border-0" style="border-radius: 20px;">
            <crear id_c="{{ $data['cliente'][0]->id_cliente }}"></crear>

            <div class="card border-0">
                <a href="/gestion/{{ $data['cliente'][0]->id_cliente }}"
                   class="btn btn-secondary btn-block">
                    Regresar
                </a>
            </div>
        </div>

@endsection
