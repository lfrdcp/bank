@extends('layouts.app')
@section('title','Estadistica')
@section('content')
    <div class="container">
        @include('includes.barraMenu')

        <div class="card border-0" style="border-radius: 20px; margin: 15px;">
            <div class="card-title">
                <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">
                    Documentación</h1>
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

                <div class="row col">

                    <div class="card border-0 col" style="margin: 15px;">

                        <div class="card-body">
                            @if(auth()->user()->tipo =="Administrador")
                                <h5 class="card-title">Subir archivo</h5>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#exampleModalCenter">
                                    Subir un archivo
                                </button>
                        @endif
                        <!-- Modal -->
                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <form method="POST" action="/documentacion" enctype="multipart/form-data">

                                                @csrf
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="customFileLang"
                                                           lang="es"
                                                           name="archivo">
                                                    <label class="custom-file-label" for="customFileLang">Seleccionar
                                                        Archivo</label>
                                                </div>
                                                <br><br><br>
                                                <input type="text" class="form-control" name="nombre" placeholder="Ingrese nombre del archivo">
                                                <br>
                                                <button type="submit" class="btn btn-primary">Subir archivo
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="card col">
                        <div class="card-body">
                            <h5 class="card-title">Ver archivo</h5>
                            @for($i=0;$i<count($data);$i++)
                                <h1>{{$data[$i]->nombre}}</h1>
                                <a href="{{'/'.auth()->user()->despacho.'/'.$data[$i]->ruta}}"
                                   target="_blank">
                                    <button class="btn btn-outline-primary btn-block text-primary"
                                            type="submit" style="font-size: 12px; border-radius: 20px;">
                                        Ver
                                    </button>
                                </a>
                            @endfor
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div style="margin: 30px;">
            <a href="/"
               class="btn btn-secondary btn-block">Regresar</a>
        </div>
@endsection



