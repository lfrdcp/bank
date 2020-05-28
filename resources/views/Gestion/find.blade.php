@extends('layouts.app')
@section('title','Resultado de busqueda')
@section('content')
    <div class="container">
        @include('includes.barraMenu')

        <div class="card border-0" style="border-radius: 20px; margin: 15px;">
            <div class="card-title">
                <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Resultados
                    de la busqueda</h1>
            </div>
        </div>


        <div class="card border-0 shadow-lg col" style="border-radius: 20px; margin: 15px;">
            <div class="card-body">

                <table class="table table-light table-striped table-bordered" style="font-size: 11px;">
                    <thead>
                    <tr class="bg-primary text-white">
                        <td colspan="col" class="text-center" style="font-size: 16px;">No. cliente</td>
                        <td colspan="col" class="text-center" style="font-size: 16px;">Titular</td>
                        <td colspan="col" class="text-center" style="font-size: 16px;">Aval</td>
                        <td colspan="col" class="text-center" style="font-size: 16px;">Encargado</td>
                        <td colspan="col" class="text-center" style="font-size: 16px;">Saldo total</td>
                        <td colspan="col" class="text-center" style="font-size: 16px;">Acción</td>
                    </tr>
                    </thead>
                    <tbody>

                    @for ($i = 0; $i < count($data); $i++)
                        <tr>
                            <td>{{$data[$i]->id_cliente}}</td>
                            <td>{{$data[$i]->nombre}}</td>
                            <td>{{$data[$i]->nombre_aval}}</td>
                            <td>{{$data[$i]->encargado}}</td>
                            <td>$ {{number_format($data[$i]->total,2)}}</td>
                            <td><a href="/gestion/{{$data[$i]->id_cliente}}" class="btn btn-primary btn-block"
                                   style="font-size: 12px; ">Acceder</a></td>
                        </tr>
                    @endfor

                    </tbody>
                </table>

                <hr>
                <div class="col-auto my-1">
                    <a class="btn btn-block btn-secondary"
                       href="/gestion" role="button">Regresar</a>
                </div>
            </div>
        </div>
    </div>

@endsection
