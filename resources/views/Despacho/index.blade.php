@extends('layouts.app')
@section('content')
    <div class="container">
        @include('includes.barraMenu')

        <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Gestionar despachos</h1>
        <br>
        <div class="shadow-lg card border-primary" style="border-radius: 20px; margin: 15px;">
            <div class="card-body">

                <div style="display: table; margin: 0 auto;">
                    <despacho-modal-crear/>
                </div>
                <despacho-show/>
            </div>
        </div>
    </div>
@endsection
