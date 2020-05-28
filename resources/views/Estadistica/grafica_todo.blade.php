@extends('layouts.app')
@section('title','Estadistica')
@section('content')

    <div class="container">
        @include('includes.barraMenu')

        <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Gráficas de avance y estadística en directo</h1>

        <div class="card border-0" style="border-radius: 20px;">
            <div class="card-body">


                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <grafica meta="{{$data['meta']}}" acumulado="{{$data['acumulado']}}"></grafica>
                        </div>
                        <div class="carousel-item">
                            <grafica-gestor></grafica-gestor>
                        </div>
                        <div class="carousel-item">
                            <grafica-encargado></grafica-encargado>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
                <br>
                <div style="margin: 30px;">
                    <a href="#" onclick="history.go(-1)"
                       class="btn btn-secondary btn-block">Regresar</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function()
            {
                location.reload();
            }, 15000);
    </script>
@endsection



