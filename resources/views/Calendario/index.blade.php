@extends('layouts.app')
@section('title','Calendario')
@section('title','Buscar')
@section('content')
    <div  class="container">
        @include('includes.barraMenu')


        <div class="card border-0" style="border-radius: 20px; margin: 15px;">
            <div class="card-title">
                <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Calendario</h1>
            </div>
        </div>
    </div>
    <calendario></calendario>
@endsection
