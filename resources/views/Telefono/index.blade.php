@extends('layouts.app')
@section('content')
    <div class="container">
        @include('includes.barraMenu')

        <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Gestionar telefonos</h1>
        <br>

        <div class="shadow-lg card border-primary" style="border-radius: 20px; margin: 15px;">
            <div class="card-body" id="first">

                <telefono-component id_c="{{$data['id_cliente']}}"></telefono-component>
                <div style="margin: 30px;">
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("first").setAttribute("align", "center");
    </script>
@endsection
