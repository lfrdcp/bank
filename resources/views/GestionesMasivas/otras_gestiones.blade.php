@extends('layouts.appSinJava')
@section('title','Gestiones másivas')
@section('content')
    <div class="container">
        @include('includes.barraMenu')

        <div class="card border-0" style="border-radius: 20px; margin: 15px;">
            <div class="card-title">
                <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Otras gestiones</h1>
                @if (session('msj'))
                    <div class="alert alert-secondary" role="alert">
                        <h3>{{session('msj')}}</h3>
                    </div>
                @endif
                {!!$errors->first('archivo','<div class="alert alert-warning" role="alert" style="border-radius: 20px;">:message</div>')!!}
            </div>
        </div>


        <div class="row col">

            <div class="card col border-0">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <img src="{{asset('imagesTecmor/manual.jpg')}}" class="card-img-top">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Manual</h5>
                            <form action="/vaciarBase" method="POST" enctype="multipart/form-data" class="was-validated">
                                @csrf
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFileLang" lang="es"
                                           name="archivo">
                                    <input type="numeric" hidden value="3" name="opcion">
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
        </div>
        <br>
        <br>
        <br>
        <br>
    </div>
@endsection
