@extends('layouts.app')
@section('title','Detalles de cliente')
@section('content')
    <div class="container">
        @include('includes.barraMenu')

        <div class="card border-0" style="border-radius: 20px; margin: 15px;">
            <div class="card-title">
                <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Detalles
                    de cliente</h1>
            </div>
        </div>


        {!!$errors->first('Documento','<div class="alert alert-warning" role="alert" style="border-radius: 20px;">:message</div>')!!}
        @if (session('msj'))
            <div class="alert alert-success" role="alert" style="border-radius: 20px;">
                <strong>{{ session('msj') }}</strong>
            </div>
    @endif


    <!-- Modal de pago-->
        <div class="modal fade" id="verpdfs" style="border-radius: 20px; margin: 15px;">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <h4>Lista de PDF(s)</h4>
                            <table class="table">
                                <tr class="bg-primary text-white">
                                    <th scope="col"><h5>{{ $data['cliente'][0]->id_cliente }}</h5></th>
                                    <th scope="col"><h5>{{ $data['cliente'][0]->nombre }}</h5></th>
                                    <th scope="col"><h5>Saldo: $ {{ number_format($data['pago'][0]->total,2) }}</h5>
                                    </th>
                                </tr>
                            </table>

                            <table class="table">
                                <tr>
                                    <th scope="col"><h5>Fecha</h5></th>
                                    <th scope="col"><h5>Acción</h5></th>
                                </tr>
                                @for ($i = 0; $i <count($data['pdfs']) ; $i++)
                                    <tr>
                                        <td> {{$data['pdfs'][$i]->fecha}}</td>

                                        <td>


                                            <a href="{{'/'.auth()->user()->despacho.'/'.$data['pdfs'][$i]->ruta}}"
                                               target="_blank">
                                                <button class="btn btn-outline-primary btn-block text-primary"
                                                        type="submit" style="font-size: 12px; border-radius: 20px;">
                                                    Ver
                                                </button>
                                            </a>

                                        </td>


                                    </tr>
                                @endfor
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal de pago-->
        <div class="modal fade" id="pagos" style="border-radius: 20px; margin: 15px;">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">

                        <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <h4>Realizar pagos para el convenio</h4>

                            <table class="table">
                                <tr class="bg-primary text-white">
                                    <th scope="col"><h5>{{ $data['cliente'][0]->id_cliente }}</h5></th>
                                    <th scope="col"><h5>{{ $data['cliente'][0]->nombre }}</h5></th>
                                    <th scope="col"><h5>Saldo: $ {{ number_format($data['pago'][0]->total,2) }}</h5>
                                    </th>
                                </tr>
                            </table>
                            <gestion-pago id_c="{{ $data['cliente'][0]->id_cliente }}" tipo="{{auth()->user()->tipo}}"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal de pago-->
        <div class="modal fade" id="pagosIntencion" style="border-radius: 20px; margin: 15px;">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">

                        <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <h4>Realizar pago intención</h4>
                            <table class="table">
                                <tr class="bg-primary text-white">
                                    <th scope="col"><h5>{{ $data['cliente'][0]->id_cliente }}</h5></th>
                                    <th scope="col"><h5>{{ $data['cliente'][0]->nombre }}</h5></th>
                                    <th scope="col"><h5>Saldo: $ {{ number_format($data['pago'][0]->total,2) }}</h5>
                                    </th>
                                </tr>
                            </table>
                            <confirmar id_c="{{ $data['cliente'][0]->id_cliente }}" tipo="{{auth()->user()->tipo}}"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal de Lista convenios-->
        <div class="modal fade" id="ListaConvenios" style="border-radius: 20px; margin: 15px;">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <h4>Lista de convenios</h4>
                            <table class="table">
                                <tr class="bg-primary text-white">
                                    <th scope="col"><h5>{{ $data['cliente'][0]->id_cliente }}</h5></th>
                                    <th scope="col"><h5>{{ $data['cliente'][0]->nombre }}</h5></th>
                                    <th scope="col"><h5>Saldo: $ {{ number_format($data['pago'][0]->total,2) }}</h5>
                                    </th>
                                </tr>
                            </table>
                            <gestion-convenio-show id_c="{{ $data['cliente'][0]->id_cliente }}"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Lista convenios-->
        <div class="modal fade" id="ListaGestiones" style="border-radius: 20px; margin: 15px;">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <h4>Lista de gestiones</h4>
                            <table class="table">
                                <tr class="bg-primary text-white">
                                    <th scope="col"><h5>{{ $data['cliente'][0]->id_cliente }}</h5></th>
                                    <th scope="col"><h5>{{ $data['cliente'][0]->nombre }}</h5></th>
                                    <th scope="col"><h5>Saldo: $ {{ number_format($data['pago'][0]->total,2) }}</h5>
                                    </th>
                                </tr>
                            </table>
                            <gestiones-show id_c="{{ $data['cliente'][0]->id_cliente }}"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Gestionar Telefonos-->
        <div class="modal fade" id="GestionarTelefonos" style="border-radius: 20px; margin: 15px;">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                            <b><span aria-hidden="true">&times;</span></b>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4>Gestionar teléfonos</h4>
                        <div>
                            <table class="table">
                                <tr class="bg-primary text-white">
                                    <th scope="col"><h5>{{ $data['cliente'][0]->id_cliente }}</h5></th>
                                    <th scope="col"><h5>{{ $data['cliente'][0]->nombre }}</h5></th>
                                    <th scope="col"><h5>Saldo: $ {{ number_format($data['pago'][0]->total,2) }}</h5>
                                    </th>
                                </tr>
                            </table>
                            <telefono-component id_c="{{ $data['cliente'][0]->id_cliente }}"
                                                tipo_usuario="{{auth()->user()->tipo}}"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Crear Gestion Convenio Nota-->
        <div class="modal fade" id="crearGestion" style="border-radius: 20px; margin: 15px;">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Crear gestión</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <crear id_c="{{ $data['cliente'][0]->id_cliente }}"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="card col border-0 shadow-lg " style="border-radius: 20px;">
            <div class="card-body">
                <table class="table">
                    <tr class="bg-primary text-white">
                        <th scope="col"><h5>{{ $data['cliente'][0]->id_cliente }}</h5></th>
                        <th scope="col"><h5>{{ $data['cliente'][0]->nombre }}</h5></th>
                        <th scope="col"><h5>Saldo: $ {{ number_format($data['pago'][0]->total,2) }}</h5></th>
                    </tr>
                </table>
                <br>


                @if($data['convenio_o_intencion']== FALSE)

                    @if($data['estado_intencion']=='0')
                        <div class="alert alert-success col"
                             role="alert">
                            PAGO INTENCIÓN ACTIVO
                        </div>
                    @elseif($data['estado_intencion']=='1')
                        <div class="alert alert-danger col"
                             role="alert">
                            PAGO INTENCIÓN CANCELADO
                        </div>
                    @elseif($data['estado_intencion']=='2')
                        <div class="alert alert-success col"
                             role="alert">
                            PAGO INTENCIÓN PAGADO
                        </div>
                    @endif


                @else

                    @if(is_null($data['convenio']))
                        <div class="alert alert-danger col"
                             role="alert">
                            SIN CONVENIO
                        </div>
                    @else

                        @if($data['convenio']->deuda_total == 0)

                            @if($data['convenio']->numero_pagos==0)
                                <div class="alert alert-success col"
                                     role="alert">
                                    CUENTA LIQUIDADA
                                </div>
                            @else
                                <div class="alert alert-success col"
                                     role="alert">
                                    CONVENIO CUMPLIDO
                                </div>
                            @endif
                        @elseif($data['convenio']->status=='CONVENIO PENDIENTE')
                            @if($data['convenio']->numero_pagos==0)
                                <div class="alert alert-warning col"
                                     role="alert">LIQUIDACIÓN PENDIENTE
                                </div>
                            @else
                                <div class="alert alert-warning col"
                                     role="alert">
                                    CONVENIO PENDIENTE
                                </div>
                            @endif
                        @elseif($data['convenio']->status=='CONVENIO CANCELADO')
                            @if($data['convenio']->numero_pagos==0)
                                <div class="alert alert-danger col"
                                     role="alert">LIQUIDACIÓN CANCELADA
                                </div>
                            @else
                                <div class="alert alert-danger col"
                                     role="alert">
                                    CONVENIO CANCELADO
                                </div>
                            @endif
                        @elseif($data['convenio']->status=='CONVENIO ACTIVO')
                            @if($data['convenio']->numero_pagos==0)
                                <div class="alert alert-success col"
                                     role="alert">LIQUIDACIÓN ACTIVA
                                </div>
                            @else
                                <div class="alert alert-success col"
                                     role="alert">
                                    CONVENIO ACTIVO
                                </div>
                            @endif
                        @endif



                    @endif
                @endif


                <div class="row">

                    <div class="card-transparent border-0 col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3"
                         style="font-size: 14px;">
                        <table class="table table-light table-striped table-bordered" style="font-size: 11px;">
                            <tbody>
                            <tr>
                                <td colspan="2" class="text-center" style="font-size: 16px;"><b>General</b></td>
                            </tr>
                            <tr>
                                <td><b>Contrato</b></td>
                                <td>{{ $data['cliente'][0]->id_cliente }}</td>
                            </tr>
                            <tr>
                                <td><b>Nombre del titular</b></td>
                                <td>{{ $data['cliente'][0]->nombre }}</td>
                            </tr>
                            <tr>
                                <td><b>ID de grupo</b></td>
                                <td>{{ $data['cliente'][0]->id_grupo }}</td>
                            </tr>
                            <tr>
                                <td><b>Nombre de grupo</b></td>
                                <td>{{ $data['cliente'][0]->nombre_grupo }}</td>
                            </tr>
                            <tr>
                                <td><b>RFC</b></td>
                                <td>{{ $data['cliente'][0]->rfc }}</td>
                            </tr>
                            <tr>
                                <td><b>Nombre del encargado</b></td>
                                <td>{{ $data['cliente'][0]->encargado }}</td>
                            </tr>
                            <tr>
                                <td><b>Gerencia</b></td>
                                <td>{{ $data['cliente'][0]->gerencia }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center" style="font-size: 16px;"><b>Trabajo(s)</b></td>
                            </tr>
                            @if(empty($data['trabajos']))
                                <tr>
                                    <td colspan="2" class="text-center">No tiene datos de trabajo</td>
                                </tr>
                            @endif
                            @for ($k = 0; $k <count($data['trabajos']) ; $k++)
                                @if (!empty($data['trabajos']))
                                    <tr>
                                        <td colspan="2" class="text-center">-</td>
                                    </tr>
                                    <tr>
                                        <td><b>Teléfono de trabajo</b></td>
                                        <td>{{ $data['trabajos'][$k]->num_tel }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="2" class="text-center">-</td>
                                    </tr>
                                @endif
                                @for ($i = 0; $i <count($data['direcciones']) ; $i++)
                                    @if ($data['direcciones'][$i]->tipo_direccion == "trabajo")
                                        @if (!is_null($data['direcciones'][$i]->direccion))
                                            <tr>
                                                <td><b>Código postal de trabajo</b></td>
                                                <td>{{ $data['direcciones'][$i]->cp }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Dirección de trabajo</b></td>
                                                <td>{{ $data['direcciones'][$i]->direccion }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Colonia de trabajo</b></td>
                                                <td>{{ $data['direcciones'][$i]->colonia }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Población de trabajo</b></td>
                                                <td>{{ $data['direcciones'][$i]->poblacion }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Estado de trabajo</b></td>
                                                <td>{{ $data['direcciones'][$i]->estado }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Número exterior de trabajo</b></td>
                                                <td>{{ $data['direcciones'][$i]->num_ext }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Número interior de trabajo</b></td>
                                                <td>{{ $data['direcciones'][$i]->num_int }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Cuadrante de trabajo</b></td>
                                                <td>{{ $data['direcciones'][$i]->cuadrante }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Zona geográfica de trabajo</b></td>
                                                <td>{{ $data['direcciones'][$i]->zona_geo }}</td>
                                            </tr>
                                        @endif
                                    @endif
                                @endfor
                            @endfor
                            </tbody>
                        </table>
                    </div>

                    <div class="card-transparent border-0 col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3"
                         style="font-size: 14px;">
                        <table class="table table-light table-striped table-bordered" style="font-size: 11px;">
                            <tbody>
                            <tr>
                                <td colspan="2" class="text-center" style="font-size: 16px;"><b>Pago</b></td>
                            </tr>
                            <tr>
                                <td><b>Clasificación del titular</b></td>
                                <td>{{ $data['pago'][0]->clasificacion }}</td>
                            </tr>
                            <tr>
                                <td><b>Atraso máximo</b></td>
                                <td>{{ number_format($data['pago'][0]->atraso_max,2) }}</td>
                            </tr>
                            <tr>
                                <td><b>Saldo</b></td>
                                <td>$ {{ number_format($data['pago'][0]->saldo,2) }}</td>
                            </tr>
                            <tr>
                                <td><b>Moratorios</b></td>
                                <td>$ {{ number_format($data['pago'][0]->moratorios,2) }}</td>
                            </tr>
                            <tr>
                                <td><b>Día de pago</b></td>
                                <td>{{ $data['pago'][0]->dia_de_pago }}</td>
                            </tr>
                            <tr>
                                <td><b>Saldo total</b></td>
                                <td>$ {{ number_format($data['pago'][0]->total,2) }}</td>
                            </tr>
                            <tr>
                                <td><b>Fecha de último pago</b></td>
                                <td>{{ $data['pago'][0]->fecha_pago_ultimo }}</td>
                            </tr>
                            <tr>
                                <td><b>Importe de último pago</b></td>
                                <td>$ {{ number_format($data['pago'][0]->importe_pago_ultimo,2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center" style="font-size: 16px;"><b>Aval(es)</b></td>
                            </tr>
                            @if (!is_null($data['datosAval']) || !empty($data['datosAval']))
                                @if (count($data['datosAval'][0]) > 0)
                                    @for ($i = 0; $i <count($data['datosAval']) ; $i++)
                                        <tr>
                                            <td colspan="2" class="text-center">-</td>
                                        </tr>
                                        <tr>
                                            <td>Nombre del aval</td>
                                            <td class="text-primary">
                                                <a data-toggle="modal"
                                                   data-target="#modalAval{{ $data['datosAval'][$i][0]->id_aval }}">
                                                    <b>{{ $data['datosAval'][$i][0]->nombre_aval }}</b>
                                                </a>
                                            </td>
                                        </tr>
                                        @php
                                            $aux = $data['telefonosAval'][$i];
                                        @endphp
                                        @for ($j = 0; $j <count($aux) ; $j++)
                                            <tr>
                                                <td><b>Teléfono {{ $j+1 }} del Aval {{ $i+1 }}:</b></td>
                                                <td>{{ $aux[$j]->numero_tel }}</td>
                                            </tr>
                                        @endfor
                                        @if(!empty($data['direccionAval'][0]))
                                            @for ($k = 0; $k <count($data['direccionAval']) ; $k++)
                                                @if($data['datosAval'][$i][0]->id_direccion==$data['direccionAval'][$k][0]->id_direccion)

                                                    <tr>
                                                        <td><b>Código postal del aval</b></td>
                                                        <td>{{ $data['direccionAval'][$k][0]->cp }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Dirección del aval</b></td>
                                                        <td>{{ $data['direccionAval'][$k][0]->direccion }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Colonia del aval</b></td>
                                                        <td>{{ $data['direccionAval'][$k][0]->colonia }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Población del aval</b></td>
                                                        <td>{{ $data['direccionAval'][$k][0]->poblacion }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Estado del aval</b></td>
                                                        <td>{{ $data['direccionAval'][$k][0]->estado }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Número exterior del aval</b></td>
                                                        <td>{{ $data['direccionAval'][$k][0]->num_ext }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Número interior del aval</b></td>
                                                        <td>{{ $data['direccionAval'][$k][0]->num_int }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Cuadrante del aval</b></td>
                                                        <td>{{ $data['direccionAval'][$k][0]->cuadrante }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Zona geográfica del aval</b></td>
                                                        <td>{{ $data['direccionAval'][$k][0]->zona_geo }}</td>
                                                    </tr>
                                                @endif
                                            @endfor
                                        @endif

                                    @endfor

                                @else
                                    <tr>{{-- No tiene id_aval registrado en tabla aval --}}
                                        <td><b>Aval:</b></td>
                                        <td>No tiene aval</td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td colspan="2" class="text-center">No tiene datos de aval</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="card-transparent border-0 col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3"
                         style="font-size: 14px;">
                        <table class="table table-light table-striped table-bordered" style="font-size: 11px;">
                            <tbody>
                            @for ($i = 0; $i <count($data['direcciones']) ; $i++)
                                @if ($data['direcciones'][$i]->tipo_direccion == "casa")
                                    <tr>
                                        <td colspan="2" class="text-center" style="font-size: 16px;">
                                            <b>Domicilio(s)</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center">-</td>
                                    </tr>
                                    <tr>
                                        <td><b>Código postal</b></td>
                                        <td>{{ $data['direcciones'][$i]->cp }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Dirección</b></td>
                                        <td>{{ $data['direcciones'][$i]->direccion }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Colonia</b></td>
                                        <td>{{ $data['direcciones'][$i]->colonia }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Población</b></td>
                                        <td>{{ $data['direcciones'][$i]->poblacion }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Estado</b></td>
                                        <td>{{ $data['direcciones'][$i]->estado }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Número exterior</b></td>
                                        <td>{{ $data['direcciones'][$i]->num_ext }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Número interior</b></td>
                                        <td>{{ $data['direcciones'][$i]->num_int }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Cuadrante</b></td>
                                        <td>{{ $data['direcciones'][$i]->cuadrante }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Zona geográfica</b></td>
                                        <td>{{ $data['direcciones'][$i]->zona_geo }}</td>
                                    </tr>
                                @endif
                            @endfor

                            <tr>
                                <td><b>Cuenta en cyber</b></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center" style="font-size: 16px;"><b>Teléfono(s)</b></td>
                            </tr>
                            @if(empty($data['telefonos']))
                                <tr>
                                    <td colspan="2" class="text-center">No tiene teléfono(s)</td>
                                </tr>
                            @else
                                @for ($i = 0; $i <count($data['telefonos']) ; $i++)
                                    @if (!(is_null($data['telefonos'][$i]->numero_tel)))
                                        <tr>
                                            <td>Teléfono {{ $i+1 }}:</td>
                                            <td class="text-primary">
                                                <a data-toggle="modal"
                                                   data-target="#modalTel{{ $i }}">
                                                    <b>{{ $data['telefonos'][$i]->numero_tel }}</b>
                                                </a>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td><b>Telefono:</b></td>
                                            <td>No tiene teléfono {{ $i+1 }}</td>
                                        </tr>
                                    @endif
                                @endfor
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-transparent border-0 col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 offset-md-0">
                        <div class="card-body text-center">
                            <p>Crear:</p>
                            <a href="/gestionConvenio/{{ $data['cliente'][0]->id_cliente }}"
                               style="font-size: 12px; "
                               class="btn btn-primary btn-block">
                                Gestión
                            </a>

                            <a href="/gestionPagoIntencion/{{ $data['cliente'][0]->id_cliente }}"
                               style="font-size: 12px; "
                               class="btn btn-primary btn-block">
                                Pago intención
                            </a>

                            <hr class="border-dark">
                            <p>PDF</p>

                            <button style="font-size: 12px; "
                                    class="btn btn-primary btn-block" data-toggle="modal"
                                    data-target="#verpdfs">
                                Ver PDF(s)
                            </button>
                            <br>
                            <div class="card  border-primary">
                                <div class="card-body">
                                    <form method="POST" action="/subirpdf" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="Documento" class="custom-file-input"
                                                       id="customFileLang" lang="es"
                                                       style="font-size: 5px;">
                                                <input type="numeric" hidden value="3" name="opcion">
                                                <label class="custom-file-label" for="customFileLang"
                                                       style="font-size: 8px;">Seleccionar. PDF</label>
                                            </div>
                                            <input type="text" name="id_cliente"
                                                   value="{{ $data['cliente'][0]->id_cliente }}"
                                                   hidden>
                                            <button class="btn btn-primary btn-block"
                                                    type="submit" style="font-size: 12px; ">Listo, subir
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr class="border-dark">

                            @if(!is_null($data['convenio']))
                                @if($data['convenio']->status !== 'CONVENIO CANCELADO')
                                    @if($data['convenio_o_intencion'])
                                        <button style="font-size: 12px; "
                                                class="btn btn-primary btn-block" data-toggle="modal"
                                                data-target="#pagos">
                                            Realizar pago de convenio
                                        </button>
                                    @endif
                                @endif
                            @endif

                            @if($data['convenio_o_intencion'] == FALSE)
                                <button style="font-size: 12px; "
                                        class="btn btn-primary btn-block" data-toggle="modal"
                                        data-target="#pagosIntencion">
                                    Realizar el pago intención
                                </button>
                            @endif

                            <button style="font-size: 12px; "
                                    class="btn btn-primary btn-block" data-toggle="modal"
                                    data-target="#ListaConvenios">
                                Convenios
                            </button>

                            <button style="font-size: 12px; "
                                    class="btn btn-primary btn-block" data-toggle="modal"
                                    data-target="#ListaGestiones">
                                Gestiones
                            </button>
                            <hr class="border-dark">
                            <p>Gestionar:</p>
                            <button style="font-size: 12px; "
                                    class="btn btn-primary btn-block" data-toggle="modal"
                                    data-target="#GestionarTelefonos">
                                Teléfono(s)
                            </button>

                            <a href="/agregarDatosCliente/{{ $data['cliente'][0]->id_cliente }}"
                               style="font-size: 12px; "
                               class="btn btn-primary btn-block">
                                Domicilio(s)
                            </a>

                            <a href="/trabajo/{{ $data['cliente'][0]->id_cliente }}"
                               style="font-size: 12px; "
                               class="btn btn-primary btn-block">
                                Trabajo(s)
                            </a>

                            <a href="/agregarDatosAval/{{ $data['cliente'][0]->id_cliente }}"
                               style="font-size: 12px;"
                               class="btn btn-primary btn-block">
                                Aval(es)
                            </a>

                            <hr class="border-dark">

                            <a href="/gestion" style="font-size: 12px;"
                               class="btn btn-secondary btn-block">
                                Regresar
                            </a>
                        </div>
                    </div>


                </div>

            </div>

        </div>

    </div>

    {{-- INICIA MODAL DE LOS AVALES DEL CLIENTE GENERAL --}}
    @if (!is_null($data['datosAval']) || !empty($data['datosAval']))
        @if (count($data['datosAval'][0]) > 0)
            @for ($i = 0; $i <count($data['datosAval']) ; $i++)
                <div class="modal fade"
                     id="modalAval{{ $data['datosAval'][$i][0]->id_aval }}">{{-- INICIA EL MODAL AVAL--}}
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content" style="border-radius: 20px;">
                            <div class="modal-header">{{-- INICIA CABECERA MODAL --}}
                                <h5 class="modal-title ">Clientes que tienen al
                                    Aval: {{ $data['datosAval'][$i][0]->nombre_aval }}</h5>
                                <button class="close" data-dismiss="modal">&times;</button>
                            </div>{{-- TERMINA CABECERA MODAL --}}
                            <div class="modal-body">{{-- INICIA CUERPO MODAL --}}
                                @if ($data['datosAval'][$i][0]->id_aval == $data['auxListaClientes'][$i][0]->id_aval)
                                    <table class="table">
                                        <tbody>
                                        <thead>
                                        <tr class="bg-primary text-white">
                                            <th>Contrato:</th>
                                            <th>Nombre del cliente:</th>
                                            <th>Acción</th>
                                        </tr>
                                        </thead>
                                        @for ($j = 0; $j <count($data['auxListaClientes'][$i]) ; $j++)
                                            <tr>
                                                <th scope="row"> {{ $data['listaClientes'][$i][$j][0]->id_cliente }}</th>
                                                <td> {{ $data['listaClientes'][$i][$j][0]->nombre }}</td>
                                                <td><a class="btn btn-outline-primary"
                                                       href="/gestion/{{ $data['listaClientes'][$i][$j][0]->id_cliente }}"
                                                       role="button" style="font-size: 14px; border-radius: 20px;"
                                                       target="_blank">Acceder</a></td>
                                            </tr>
                                        @endfor

                                    </table>
                                    <hr>
                                @endif
                            </div>{{-- TERMINA CUERPO MODAL --}}
                        </div>
                    </div>
                </div>{{-- TERMINA EL MODAL --}}
            @endfor
        @endif
    @endif
    {{-- TERMINA MODAL DE LOS AVALES DEL CLIENTE GENERAL --}}

    {{-- INICIA MODAL DE LOS TELEFONOS DEL CLIENTE GENERAL --}}
    @for ($i = 0; $i <count($data['telefonos']) ; $i++)
        @if (!(is_null($data['telefonos'][$i]->numero_tel)))
            <div class="modal fade" id="modalTel{{ $i }}">{{-- INICIA EL MODAL AVAL--}}
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content" style="border-radius: 20px;">
                        <div class="modal-header">{{-- INICIA CABECERA MODAL --}}
                            <h5 class="modal-title ">Clientes que tienen el teléfono {{ $i+1 }}
                                : {{ $data['telefonos'][$i]->numero_tel }}</h5>
                            <button class="close" data-dismiss="modal">&times;</button>
                        </div>{{-- TERMINA CABECERA MODAL --}}
                        <div class="modal-body">{{-- INICIA CUERPO MODAL --}}
                            <table class="table table-striped table-bordered table-hover">
                                <tbody>
                                <thead>
                                <tr class="bg-primary text-white">
                                    <th>Contrato:</th>
                                    <th>Nombre del cliente:</th>
                                    <th>Acción</th>
                                </tr>
                                </thead>
                                @for ($j = 0; $j <count($data['listaDatosClientesXTel'][$i]) ; $j++)
                                    <tr>
                                        <th>{{ $data['listaDatosClientesXTel'][$i][$j]->id_cliente }}</th>
                                        <th>{{ $data['listaDatosClientesXTel'][$i][$j]->nombre }}</th>
                                        <td><a class="btn btn-outline-primary"
                                               href="/gestion/{{ $data['listaDatosClientesXTel'][$i][$j]->id_cliente }}"
                                               role="button" style="font-size: 14px; border-radius: 20px;"
                                               target="_blank">Acceder</a></td>
                                    </tr>
                                @endfor

                            </table>
                        </div>{{-- TERMINA CUERPO MODAL --}}

                    </div>

                </div>
            </div>{{-- TERMINA EL MODAL --}}
        @endif
    @endfor
    {{-- TERMINA MODAL DE LOS AVALES DEL CLIENTE GENERAL --}}

@endsection

