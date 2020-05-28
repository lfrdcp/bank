@extends('layouts.appSinJava')
@section('title','Gestiones másivas')
@section('content')
    <div class="container">
        @include('includes.barraMenu')

        <div class="card border-0" style="border-radius: 20px; margin: 15px;">
            <div class="card-title">
                <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Avance de campaña</h1>
                @if (session('msj'))
                    <div class="alert alert-secondary" role="alert">
                        <h3>{{session('msj')}}</h3>
                    </div>
                @endif
                {!!$errors->first('archivo','<div class="alert alert-warning" role="alert" style="border-radius: 20px;">:message</div>')!!}
            </div>
        </div>


        <div class="row col">

            <div class="card col-12 col-sm-12 col-md-10 col-lg-10 col-xl-10 border-0">
                <div class="row no-gutters">
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <img src="{{asset('imagesTecmor/conmutador.png')}}" class="card-img-top">
                    </div>
                    <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                        <div class="card-body">
                            <h5 class="card-title">Conmutador</h5>
                            <form method="POST" action="/vaciarBase" enctype="multipart/form-data">
                                @csrf
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFileLang" lang="es"
                                           name="archivo">
                                    <input type="numeric" hidden value="4" name="opcion">
                                    <label class="custom-file-label" for="customFileLang">Seleccionar Archivo</label>
                                </div>
                                <br><br>
                                <button class="btn btn-primary" type="submit">Subir
                                    archivo
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col">
                <div class="card-body">
                    <h5 class="card-title">sistema viejo</h5>
                    <form method="POST" action="/vaciarBase" enctype="multipart/form-data">
                        @csrf
                        <div class="custom-file">
                            <input type="numeric" hidden value="10" name="opcion">

                        </div>
                        <br><br>
                        <button class="btn btn-primary" type="submit">Subir
                            archivo
                        </button>
                    </form>
                </div>
            </div>

        </div>
        <br>
        <br>
        <br>
        <br>
    </div>
@endsection
