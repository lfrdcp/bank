@extends('layouts.app')
@section('title','Gestionar usuarios')
@section('content')
    <div class="container">
        @include('includes.barraMenu')
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="border-radius: 20px;">
                <li class="breadcrumb-item">Inicio</li>
                <li class="breadcrumb-item text-primary">Gestionar usuarios</li>
            </ol>
        </nav>
        <h1 class="text-center text-secondary">Gestionar usuarios</h1>
        <usuario-show-despacho></usuario-show-despacho>
    </div>
@endsection



