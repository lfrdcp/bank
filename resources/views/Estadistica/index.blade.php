@extends('layouts.app')
@section('title','Estadistica')
@section('content')
    <div class="container">
        @include('includes.barraMenu')

        <div class="card border-0" style="border-radius: 20px; margin: 15px;">
            <div class="card-title">
                <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">
                    Estadistica</h1>
                @if (session('msj'))
                    <input id="Idbienvenido" type="hidden" value="1">
                    <div class="alert alert-secondary" role="alert">
                        <h3>{{session('msj')}}</h3>
                    </div>
                @endif
            </div>
        </div>
        <div class="card border-0" style="border-radius: 20px;">
            <div class="card-body">

                <div class="card-columns">
                    <div class="card border-0" style="margin: 15px;">
                        <img src="{{asset('imagesTecmor/grafica_capturar.jpg')}}" class="card-img-top">
                        <div class="card-body">
                            @if($data['permiso_general'])
                                <h5 class="card-title">Editar meta semanal</h5>
                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                        data-target="#exampleModalCenter">
                                    Editar
                                </button>
                            @else
                                <h5 class="card-title">Introducir meta semanal</h5>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#exampleModalCenter">
                                    Introducir
                                </button>
                        @endif




                        <!-- Modal -->
                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            @if($data['permiso_general'])
                                                <h5 class="modal-title" id="exampleModalLongTitle">Editar meta
                                                    semanal: {{$data['week']}}</h5>
                                            @else
                                                <h5 class="modal-title" id="exampleModalLongTitle">Introducir meta
                                                    semanal: {{$data['week']}}</h5>
                                            @endif

                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            @if($data['permiso_general'])
                                                <form method="PUT"
                                                      action="/estadistica/{{$data['grafica_general']->id_grafica}}/edit">
                                                    @csrf
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" placeholder="Monto"
                                                               name="monto" value="{{$data['grafica_general']->monto}}">

                                                    </div>
                                                    <button type="submit" class="btn btn-warning">Editar</button>
                                                </form>
                                            @else
                                                <form method="POST" action="/estadistica">
                                                    @csrf
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" placeholder="Monto"
                                                               name="monto">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Introducir
                                                    </button>
                                                </form>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($data['permiso_general'])
                        <div class="card border-0" style="margin: 15px;">
                            <img src="{{asset('imagesTecmor/grafica_general.jpg')}}" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title">Gráfica de avance general</h5>
                                <a href="/grafica_general_index" class="btn btn-primary">Acceder</a>
                            </div>
                        </div>
                    @endif

                    <div class="card border-0" style="margin: 15px;">
                        <img src="{{asset('imagesTecmor/grafica_encargado_gestor.jpg')}}" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">Gráfica de avance gestor por encargado</h5>
                            <a href="/grafica_gestor_encargado_index" class="btn btn-primary">Acceder</a>
                        </div>
                    </div>

                </div>


                <div class="card-columns">

                    <div class="card border-0" style="margin: 15px;">
                        <img src="{{asset('imagesTecmor/grafica_encargado.jpg')}}" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">Gráfica de avance por encargado</h5>
                            @if($data['permiso_encargado'])
                                <a href="/grafica_encargado_index" class="btn btn-primary">Acceder</a>
                            @else
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#modal_encargado">
                                    Introducir
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="modal_encargado" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Introducir meta
                                                    por encargado</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="/grafica_encargado">
                                                    @csrf
                                                    @if(!is_null($data['encargados']))
                                                        @php
                                                            $tam_encargado = count($data['encargados']);
                                                        @endphp
                                                        <input type="numeric" class="form-control"
                                                               name="tam" value="{{$tam_encargado}}" hidden>
                                                        @for($i=0;$i<count($data['encargados']);$i++)
                                                            <div class="form-group row">
                                                                <label
                                                                    class="col-sm-6 col-form-label">{{$data['encargados'][$i]->encargado}}</label>
                                                                <div class="col-sm-6">
                                                                    <input type="numeric" class="form-control"
                                                                           placeholder="Ingrese monto para el encargado"
                                                                           name="{{$i}}" required>

                                                                    <input type="text" hidden name="{{$i.'-'}}"
                                                                           value="{{$data['encargados'][$i]->encargado}}">
                                                                </div>
                                                            </div>
                                                        @endfor
                                                    @endif
                                                    <button type="submit" class="btn btn-primary">Introducir
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>


                    <div class="card border-0" style="margin: 15px;">
                        <img src="{{asset('imagesTecmor/grafica_gestor.jpg')}}" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">Gráfica de avance por gestor</h5>
                            <a href="/grafica_gestor_index" class="btn btn-primary">Acceder</a>
                        </div>
                    </div>

                    <div class="card border-0" style="margin: 15px;">
                        <img src="{{asset('imagestecmor/grafica_todo.jpg')}}" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">Gráficas de avance y estadística en directo</h5>
                            @if($data['permiso_general'] && $data['permiso_encargado'])
                                <a href="/grafica_todo" class="btn btn-primary">Acceder</a>
                            @else
                                <button type="button" class="btn btn-lg btn-primary" disabled>Falta capturar datos a
                                    gráficas
                                </button>
                            @endif
                        </div>
                    </div>


                </div>
                <div style="margin: 30px;">
                    <a href="/"
                       class="btn btn-secondary btn-block">Regresar</a>
                </div>
            </div>
        </div>

@endsection



