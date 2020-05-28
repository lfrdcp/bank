@extends('layouts.app')
@section('title','Estadistica')
@section('content')

    <div class="container">
        @include('includes.barraMenu')

        <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Gráfica de avance por encargado</h1>
        <div class="card border-0" style="border-radius: 20px;">
            <div class="card-body">
                <grafica-encargado></grafica-encargado>
                <br>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                    Ver gráfica de otra semana
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Ver gráfica de otra semana</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Da clic a una celda para ver la gráfica</p>

                                <div class="list-group">
                                    @for($i=0;$i<count($data);$i++)
                                        @php
                                            $date = new DateTime($data[$i]->fecha);
                                            $week = $date->format("W");
                                         $year = $date->format("Y");
                                        @endphp
                                        <a href="/grafica_encargado_pasado/{{$data[$i]->fecha}}"
                                           class="list-group-item list-group-item-action">
                                            <b>Año: {{$year}} | Semana: {{$week}}</b>
                                        </a>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin: 30px;">
                    <a href="/estadistica"
                       class="btn btn-secondary btn-block">Regresar</a>
                </div>
            </div>
        </div>
    </div>
@endsection



