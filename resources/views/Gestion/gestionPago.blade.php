@extends('layouts.app')
@section('title','Gestion')
@section('content')
    <div class="container">
        @include('includes.barraMenu')

        <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Realizar pago</h1>
        @if (session('msj'))
            <div class="alert alert-success" role="alert" style="border-radius: 20px;">
                <strong>{{ session('msj') }}</strong>
            </div>
        @endif

        <div class="card shadow-lg border-primary" style="border-radius: 20px;">
            <div class="row" style="margin: 4px;">
                <div class="card col-12 border-0">
                    <div class="card-body">


                        <table class="table">
                            <tr class="bg-primary text-white">
                                <th scope="col"><h5>{{ $data['cliente']->id_cliente }}</h5></th>
                                <th scope="col"><h5>{{ $data['cliente']->nombre }}</h5></th>
                                <th scope="col"><h5>Saldo: {{$data['totales']['deudaTotal']}}</h5></th>
                            </tr>
                        </table>


                        <div class="table-responsive">
                            <table class=" col-11 table" style="margin: 40px; ">


                                <th class="text-center" style="font-size: 12px;">Saldo del plan</th>
                                <th class="text-center" style="font-size: 12px;">Avance del plan</th>
                                <th class="text-center" style="font-size: 12px;">Por pagar</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="text-center" style="font-size: 12px;">¿Cancelar convenio?</th>
                                <tbody>


                                <td>
                                    <label class="form-control bg-dark text-white text-center"
                                           style=" border-radius: 20px;">
                                        <b>{{$data['totales']['deudaOriginal']}}</b>
                                    </label>
                                </td>
                                <td>
                                    <label class="form-control bg-success text-white text-center"
                                           style=" border-radius: 20px;">
                                        <b>{{$data['totales']['deudaPagada']}}</b>
                                    </label>
                                </td>
                                <td>
                                    <label class="form-control bg-danger text-white text-center"
                                           style=" border-radius: 20px;">
                                        <b>{{$data['totales']['deudaTotal']}}</b>
                                    </label>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <label class="form-control bg-danger text-white text-center"
                                           style=" border-radius: 20px;">
                                        <a class="text-white"
                                           href="/cancelarConvenio/{{ $data['datosPago'][0]->folio }}/{{ $data['id_cliente'] }}">
                                            Si
                                        </a>
                                    </label>

                                </td>
                                </tbody>
                            </table>

                        </div>


                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">Número de pago</th>
                                    <th scope="col">Fecha esperada</th>
                                    <th scope="col">Monto</th>
                                    <th scope="col">¿Pago?</th>
                                    <th scope="col">¿Cuando pago?</th>
                                    <th scope="col">¿Cuanto pago?</th>
                                    <th scope="col">Folio</th>
                                    <th scope="col">Acción</th>
                                </tr>
                                </thead>

                            </table>
                        </div>
                        @for ($i = 0; $i <  count($data['datosPago']); $i++)

                            <form class="form-group" method="POST" action="/gestionPago">
                                @csrf
                                <input type="hidden" name="primerPago" value="0">
                                <input type="hidden" name="numPago" value="{{ $i+1 }} ">
                                <input type="hidden" name="id_cliente" value="{{ $data['id_cliente'] }} ">
                                <input type="hidden" name="id_calendario"
                                       value="{{ $data['datosPago'][$i]->id_calendario }} ">
                                <input type="hidden" name="folio" value="{{ $data['datosPago'][0]->folio }} ">
                                <div class="row">

                                    @if($i==0)
                                        @if($data['convenio']->numero_pagos==0)
                                            <div scope="row" style="margin-left: 150px;">
                                                <label class="form-control" readonly
                                                       style="font-size: 10px; border-radius: 20px;">Un
                                                    solo pago(Liquidación):
                                                </label>
                                            </div>
                                        @else
                                            <div scope="row" style="margin-left: 150px;">
                                                <input type="hidden" name="primerPago" value="1">
                                                <label class="form-control" readonly
                                                       style="font-size: 10px; border-radius: 20px;">Primero
                                                    Pago:
                                                </label>
                                            </div>
                                        @endif
                                    @else
                                        <div scope="row" style="margin-left: 150px;">
                                            <label class="form-control" readonly
                                                   style="font-size: 10px; border-radius: 20px;">{{$i}}
                                            </label>
                                        </div>
                                    @endif
                                    <div><label class="form-control" readonly
                                                style="font-size: 10px; border-radius: 20px;">{{ $data['datosPago'][$i]->fecha_pago_esperada }}</label>
                                    </div>
                                    <div><label class="form-control" readonly
                                                style="font-size: 10px; border-radius: 20px;">{{ $data['datosPago'][$i]->pago_esperado }}</label>
                                    </div>
                                    <div>
                                        @if( is_null($data['datosPago'][$i]->pagado) )
                                            @if($data['totales']['deudaTotal']==0)
                                                <label class="form-control" readonly
                                                       style="font-size: 10px; border-radius: 20px;">
                                                    - - -
                                                </label>
                                            @else
                                                <select name="pagado" class="custom-select my-1 mr-sm-2"
                                                        style="font-size: 10px; border-radius: 20px;"
                                                        required>
                                                    <option selected></option>
                                                    <option value="1">Si</option>
                                                    <option value="0">No</option>
                                                </select>
                                            @endif
                                        @else
                                            <label class="form-control" readonly
                                                   style="font-size: 10px; border-radius: 20px;">
                                                {{$data['datosPago'][$i]->pagado}}
                                            </label>
                                        @endif
                                    </div>
                                    <div>
                                        @if( is_null($data['datosPago'][$i]->fecha_pago_realizada) )
                                            @if($data['totales']['deudaTotal']==0)
                                                <label class="form-control" readonly
                                                       style="font-size: 10px; border-radius: 20px;">
                                                    - - -
                                                </label>
                                            @else
                                                <input name="fecha_pago_realizada" type="date"
                                                       class="form-control"
                                                       style="font-size: 10px; border-radius: 20px;"
                                                       required>
                                            @endif
                                        @else
                                            <label class="form-control" readonly
                                                   style="font-size: 10px; border-radius: 20px;">
                                                {{$data['datosPago'][$i]->fecha_pago_realizada}}
                                            </label>
                                        @endif
                                    </div>
                                    <div>
                                        @if( is_null($data['datosPago'][$i]->pago_realizado) )
                                            @if($data['totales']['deudaTotal']==0)
                                                <label class="form-control" readonly
                                                       style="font-size: 10px; border-radius: 20px;">
                                                    - - -
                                                </label>
                                            @else
                                                <input name="pago_realizado" type="number"
                                                       class="form-control"
                                                       style="font-size: 10px; border-radius: 20px;"
                                                       required>
                                            @endif
                                        @else
                                            <label class="form-control" readonly
                                                   style="font-size: 10px; border-radius: 20px;">
                                                {{$data['datosPago'][$i]->pago_realizado}}
                                            </label>
                                        @endif
                                    </div>
                                    <div>
                                        @if( is_null($data['datosPago'][$i]->folio_ingresado) )
                                            @if($data['totales']['deudaTotal']==0)
                                                <label class="form-control" readonly
                                                       style="font-size: 10px; border-radius: 20px;">
                                                    - - -
                                                </label>
                                            @else
                                                <input name="folio_ingresado" type="text"
                                                       class="form-control "
                                                       style="font-size: 10px; border-radius: 20px;">
                                            @endif
                                        @else
                                            <label class="form-control" readonly
                                                   style="font-size: 10px; border-radius: 20px;">
                                                {{$data['datosPago'][$i]->folio_ingresado}}
                                            </label>
                                        @endif
                                    </div>
                                    <div>
                                        @if( is_null($data['datosPago'][$i]->pagado) )
                                            @if($data['totales']['deudaTotal']==0)
                                                <label class="form-control" readonly
                                                       style="font-size: 10px; border-radius: 20px;">
                                                    - - -
                                                </label>
                                            @else
                                                <button type="submit"
                                                        style="font-size: 8px; border-radius: 20px;"
                                                        class="btn btn-block btn-outline-primary">
                                                    Guardar
                                                </button>
                                            @endif
                                        @else
                                            <button type="submit"
                                                    style="font-size: 8px; border-radius: 20px;"
                                                    class="btn btn-block btn-outline-success" disabled>
                                                Pagado
                                            </button>
                                        @endif
                                    </div>

                                </div>
                            </form>

                            @if($data['convenio']->primer_pago_estado == 0)
                                @break
                            @endif
                        @endfor


                        <hr class="border-primary">
                    </div>
                </div>
            </div>
            <div class="card border-0" style="border-radius: 20px;">
                <div class="card-body text-center">
                    <a href="/gestion/{{ $data['cliente']->id_cliente }}" style="font-size: 12px; border-radius: 20px;"
                       class="btn btn-outline-secondary btn-block">Regresar</a>
                </div>
            </div>
        </div>

    </div>

@endsection
