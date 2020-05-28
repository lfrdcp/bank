@extends('layouts.app')
@section('title','Gestiones')
@section('content')
    <div class="container">

        @include('includes.barraMenu')


        @if (session('msj'))
            <div class="alert alert-warning" role="alert" style="border-radius: 20px;">
                <strong>{{ session('msj') }}</strong>
            </div>
        @endif

        <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Gestiones</h1><br>

        <div class="shadow-lg card-transparent border-primary" style="border-radius: 20px; ">
            <br>

            <div class="card-body">

                <form method="GET" action="/gestionBuscarCliente" enctype="multipart/form-data">
                    @csrf
                    <div class="col">
                        <label>Buscar por:</label>
                    </div>
                    <br>
                    <div class="col">
                        <select class="custom-select" name="opcion" required>
                            <option value="1">Cliente único</option>
                            <option selected value="2">Nombre de titular o aval</option>
                            <option value="3">Teléfono de titular</option>
                            <option value="6">Teléfono de aval</option>
                            <option value="4">Número de grupo</option>
                            <option value="5">Nombre de grupo</option>
                        </select>
                    </div>
                    <br>
                    <div class="col">
                        <input name="buscarCliente" type="text" class="form-control"
                               aria-describedby="emailHelp" required placeholder="Ingrese dato a buscar">
                        {!!$errors->first('buscarCliente','<div class="alert alert-warning" role="alert" style="border-radius: 20px;">:message</div>')!!}
                    </div>
                    <br>
                    <div class="col">
                        <button type="submit" class="btn btn-block btn-primary">
                            Buscar
                        </button>
                    </div>

                </form>
                <br>


            </div>
        </div>

    </div>

@endsection
