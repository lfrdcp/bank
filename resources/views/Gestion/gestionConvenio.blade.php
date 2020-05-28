<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tecmor - @yield('title')</title>

    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('imagesTecmor/logoFondo.png')}}"/>

    {{-- ESTILOS DE BOOTSTRAP --}}
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}" defer></script>
    <script type="text/javascript">
        function reloj() {
            var fecha = new Date();
            var hora = fecha.getHours();
            var min = fecha.getMinutes();
            var seg = fecha.getSeconds();
            var recarga = setTimeout("reloj()", 500);
            document.getElementById('print').innerHTML = hora + ":" + min + ":" + seg;
        }
    </script>

    <style>
        strong{
            font-size: 12px;
        }
        html {
            height: 100%;
        }
        body {
            height: 100%;
            background: #999999;
            background: linear-gradient(to top, #999999, #FFFFFF) no-repeat center center fixed;
        }
        footer {
            position: absolute;
            width: 100%;
        }
    </style>
</head>
@if(Auth::check())
    <body onload="reloj()" >
    @else
        <body>
        @endif
        @if(Auth::check())
            <div id="app">
                <nav class="nav navbar navbar-expand-sm shadow-lg mx-auto" style="border-radius: 20px; margin: 50px; ">
                    <div class="container">

                        <div class="row">
                            <img class="card-img-top center"src="{{asset('imagesTecmor/logofondo.png')}}" style="width: 50px; height: 50px;">
                            <div style="margin: 5px;">
                                <strong>Sistema de Gestión y Cobranza de Cartera Vencida</strong>
                            </div>
                        </div>

                        <div class="row">
                            <img class="card-img-top center" src="{{asset('imagesTecmor/cal-hor.png')}}"
                                 style="text-align: center; width: 50px; height: 50px; margin: 5px;">
                            <div style="margin: 5px;">
                                <strong>{{$arrayDate['weekday'] }} {{ date ("j") }} de {{ $arrayDate['mes'] }}
                                    del {{ date ("Y") }}</strong>
                                <br>
                                <strong>Semana: {{ $arrayDate['dt'] }} | Hora:</strong>
                                <strong id="print"></strong>
                            </div>
                        </div>

                        <div class="row">
                            <img class="card-img-top center" src="{{asset('imagesTecmor/name.jpg')}}"
                                 style="text-align: center; width: 50px; height: 50px; margin: 5px;">

                            <div style="margin: 5px;">
                                <strong> Bienvenido {{ Auth::user()->tipo }}:</strong><br>
                                <strong>{{ Auth::user()->name }} {{ Auth::user()->last_name }}</strong><br>
                            </div>
                        </div>


                    </div>
                </nav>
                @endif

                <link rel="stylesheet" href="{{asset('clockpicker/assets/css/bootstrap.min.css')}}">
                <link rel="stylesheet" href="{{asset('clockpicker/dist/bootstrap-clockpicker.min.css')}}">
                <link rel="stylesheet" href="{{asset('clockpicker/assets/css/github.min.css')}}">


                <div class="container">
                    @include('includes.barraMenu')
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Inicio</li>
                            <li class="breadcrumb-item">Gestiones</li>
                            <li class="breadcrumb-item">Resultados de la busqueda</li>
                            <li class="breadcrumb-item">Detalles de cliente</li>
                            <li class="breadcrumb-item text-primary">Crear gestión</li>
                        </ol>
                    </nav>

                    <h1 class="text-center text-secondary">Crear gestión</h1>
                    <form onsubmit="return validarUnicoPago()" class="form-group " method="POST" action="/gestion">
                        @csrf
                        <input type="hidden" name="id_cliente" value="{{ $data['cliente'][0]->id_cliente }}">
                        @if (session('msj'))
                            <div class="alert alert-warning" role="alert" style="border-radius: 20px;">
                                <strong>{{ session('msj') }}</strong>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="shadow-lg card border-primary" style="border-radius: 20px;">
                                <div class="row" style="margin: 10px;">

                                    <div class="card col-12 border-0" style="font-size: 12px;">
                                        <table class="table" style="margin: 5px;">
                                            <tr class="bg-primary text-white">
                                                <input id="id_cliente" type="hidden" value="{{ $data['cliente'][0]->id_cliente }}">
                                                <th scope="col"><h5>{{ $data['cliente'][0]->id_cliente }}</h5></th>
                                                <th scope="col"><h5>{{ $data['cliente'][0]->nombre }}</h5></th>
                                                <th scope="col"><h5>Saldo: {{ $data['pago'][0]->total }}</h5></th>
                                            </tr>
                                        </table>
                                    </div>

                                </div>

                                <div class="row" style="margin: 5px;">
                                    <div class="card col-5 border-0" style="font-size: 12px;">
                                        <table class="table table-striped table-bordered table-hover" style="font-size: 12px;">
                                            <tbody>
                                            <tr>
                                                <td><b>Contrato:</b></td>
                                                <td>{{ $data['cliente'][0]->id_cliente }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Nombre del cliente:</b></td>
                                                <td>{{ $data['cliente'][0]->nombre }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Clasificación del cliente:</b></td>
                                                <td>{{ $data['pago'][0]->clasificacion }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Atraso Maximo:</b></td>
                                                <td>{{ $data['pago'][0]->atraso_max }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Saldo:</b></td>
                                                <td>{{ $data['pago'][0]->saldo }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Moratorio</b>s:</td>
                                                <td>{{ $data['pago'][0]->moratorios }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Día de Pago:</b></td>
                                                <td>{{ $data['pago'][0]->dia_de_pago }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Saldo Total:</b></td>
                                                <td>{{ $data['pago'][0]->total }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Fecha Ultimo Pago:</b></td>
                                                <td>{{ $data['pago'][0]->fecha_pago_ultimo }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Importe Ultimo Pago:</b></td>
                                                <td>{{ $data['pago'][0]->importe_pago_ultimo }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <br>

                                        <div class="row">
                                            <div class="card col-6 border-0">
                                                <div class="form-group">
                                                    <label>Titular o Aval</label>
                                                    <select onclick="ocultarAval()" id="tit_Av" name="tit_aval"
                                                            class="form-control form-control-sm border-primary"
                                                            style="border-radius: 20px; font-size: 12px;" required>
                                                        <option selected></option>
                                                        <option value="1">Titular</option>
                                                        <option value="0">Aval</option>
                                                    </select>
                                                    {!!$errors->first('tipoGestion','<div class="alert alert-warning" role="alert" style="border-radius: 20px;">:message</div>')!!}
                                                </div>
                                            </div>

                                            <div class="card col-6 border-0">
                                                <div class="form-group">
                                                    <label>Tipo de gestión</label>
                                                    <select name="id_tipo_gestion"
                                                            class="form-control form-control-sm border-primary"
                                                            style="border-radius: 20px; font-size: 12px;" required>
                                                        <option selected></option>

                                                        @for ($i = 0; $i <count($data['tipoGestion']) ; $i++)
                                                            <option
                                                                value="{{ $data['tipoGestion'][$i]->id_tipo_gestion }}">{{ $data['tipoGestion'][$i]->nombre_gestion }}
                                                                - {{ $data['tipoGestion'][$i]->descripcion_gestion }}</option>
                                                        @endfor
                                                    </select>
                                                    {!!$errors->first('tipoGestion','<div class="alert alert-warning" role="alert" style="border-radius: 20px;">:message</div>')!!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="card col-6 border-0">
                                                <div class="form-group">
                                                    <label>Tipo de gestión ssl</label>
                                                    <select name="id_tipo_gestion_ssl"
                                                            class="form-control form-control-sm border-primary"
                                                            style="border-radius: 20px; font-size: 12px;" required>
                                                        <option selected></option>

                                                        @for ($i = 0; $i <count($data['tipoGestionSsl']) ; $i++)
                                                            <option
                                                                value="{{ $data['tipoGestionSsl'][$i]->id_tipo_gestion_ssl }}">{{ $data['tipoGestionSsl'][$i]->id_tipo_gestion_ssl }}
                                                                - {{ $data['tipoGestionSsl'][$i]->descripcion_ssl }}</option>
                                                        @endfor
                                                    </select>
                                                    {!!$errors->first('tipoGestionSsl','<div class="alert alert-warning" role="alert" style="border-radius: 20px;">:message</div>')!!}
                                                </div>
                                            </div>

                                            <div id="gest" class="card col-6 border-0">
                                                <div class="form-group">
                                                    <label>Seleccionar gestionado</label>
                                                    <select id="idGest" name="id_gestionado"
                                                            class="form-control form-control-sm border-primary"
                                                            style="border-radius: 20px; font-size: 12px;" required>
                                                        <option selected></option>

                                                        @for ($i = 0; $i <count($data['gestionado']) ; $i++)
                                                            <option
                                                                value="{{ $data['gestionado'][$i]->id_gestionado }}">{{ $data['gestionado'][$i]->id_gestionado }}
                                                                - {{ $data['gestionado'][$i]->nombre }}</option>
                                                        @endfor
                                                    </select>
                                                    {!!$errors->first('gestionado','<div class="alert alert-warning" role="alert" style="border-radius: 20px;">:message</div>')!!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card col-5 border-0" style="font-size: 12px;">
                                        <table class="table " style="font-size: 12px;">
                                            <h6>Se llega a convenio</h6>
                                            <tbody>
                                            <tr>

                                                <td></td>
                                                <td>
                                                    <div class="custom-control custom-radio ">
                                                        <input name="convenio" type="radio" id="noConvenio"
                                                               class="custom-control-input"
                                                               onClick="ocultar()" value="0">
                                                        <label class="custom-control-label" for="noConvenio">No</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-radio">
                                                        <input name="convenio" type="radio" id="siConvenio"
                                                               class="custom-control-input"
                                                               onClick="mostrar()" value="1" checked>
                                                        <label class="custom-control-label" for="siConvenio">Si</label>
                                                    </div>
                                                </td>

                                            </tr>
                                            </tbody>
                                        </table>

                                        <br>

                                        <div id="tieneConvenio">
                                            <p id="mensajeError" class="text-center text-danger"></p>
                                            <div class="row">
                                                <div class="card col-6 border-0">
                                                    <div class="form-group">
                                                        <label for="example-date-input" class="col-form-label border-primary">Fecha
                                                            de
                                                            pago inicial</label>
                                                        <input id="fechaPagoInicial" name="fechaInicial"
                                                               class="form-control border-primary" type="date"
                                                               style="border-radius: 20px; font-size: 12px;" required>
                                                        {!!$errors->first('fechaInicial','<div class="alert alert-warning" role="alert" style="border-radius: 20px;">:message</div>')!!}
                                                    </div>
                                                </div>

                                                <div class="card col-6 border-0">

                                                    <div class="form-group">
                                                        <label for="example-date-input" class="col-form-label border-primary">Pago
                                                            inicial</label>
                                                        <input id="pagoInicial" name="pagoInicial"
                                                               class="form-control border-primary" type="number"
                                                               min="1" style="border-radius: 20px; font-size: 12px;" required>
                                                        {!!$errors->first('pagoInicial','<div class="alert alert-warning" role="alert" style="border-radius: 20px;">:message</div>')!!}
                                                    </div>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row">
                                                <div class="card col-6 border-0">
                                                    <div class="form-group">
                                                        <label>Opciones de pago</label>
                                                        <select id="select" name="opcionPago"
                                                                class="form-control form-control-sm border-primary"
                                                                style="border-radius: 20px; font-size: 12px;">
                                                            <option selected></option>
                                                            @for ($i = 0; $i <= 52 ; $i++)
                                                                @if($i==0)
                                                                    <option value="{{ $i }}"> Un solo pago</option>
                                                                @else
                                                                    <option value="{{ $i }}"> Pago en {{ $i }} semanas</option>
                                                                @endif
                                                            @endfor
                                                        </select>
                                                        {!!$errors->first('opcionPago','<div class="alert alert-warning" role="alert" style="border-radius: 20px;">:message</div>')!!}
                                                    </div>
                                                </div>

                                                <div class="card col-6 border-0">
                                                    <div class="form-group">
                                                        <label>Deuda total</label>
                                                        <input id="deudaTotal" name="deudaTotal" class="form-control border-primary"
                                                               type="number"
                                                               min="1" style="border-radius: 20px; font-size: 12px;">
                                                        {!!$errors->first('deudaTotal','<div class="alert alert-warning" role="alert" style="border-radius: 20px;">:message</div>')!!}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <br>
                                        <div>
                                            <b>Volver a contactar*</b>
                                            <div class="row">
                                                <div class="card col-6 border-0">
                                                    <div class="form-group">
                                                        <label for="example-date-input" class="col-form-label">Fecha:</label>
                                                        <input name="fechaContactar" class="form-control border-primary" type="date"
                                                               style="border-radius: 20px; font-size: 12px;" required>
                                                        {!!$errors->first('fechaContactar','<div class="alert alert-warning" role="alert" style="border-radius: 20px;">:message</div>')!!}
                                                    </div>
                                                </div>

                                                <div class="card col-6 border-0">
                                                    <div class="form-group">
                                                        <label for="example-date-input" class="col-form-label">Hora:</label>
                                                        <div class="input-group clockpicker" data-placement="left" data-align="top"
                                                             data-autoclose="true">
                                                            <input name="horaContactar" type="text"
                                                                   class="form-control border-primary"
                                                                   style="border-radius: 20px; font-size: 12px;" required>
                                                            <span class="input-group-addon border-primary"
                                                                  style="border-radius: 20px; font-size: 12px;">
 											<span class="glyphicon glyphicon-time"></span>
 										</span>
                                                        </div>
                                                        <script type="text/javascript">
                                                            $('.clockpicker').clockpicker();
                                                        </script>
                                                    </div>
                                                    {!!$errors->first('horaContactar','<div class="alert alert-warning" role="alert" style="border-radius: 20px;">:message</div>')!!}
                                                </div>

                                            </div>
                                            <br>


                                            <div class="form-group">
                                                <label>Comentarios:</label>
                                                <textarea name="comentario" class="form-control border-primary" rows="5"
                                                          style="border-radius: 20px; font-size: 12px;"></textarea>
                                                {!!$errors->first('comentarioDer','<div class="alert alert-warning" role="alert" style="border-radius: 20px;">:message</div>')!!}
                                            </div>
                                        </div>

                                    </div>


                                    <div class="card col-2 border-0">
                                        <br><br><br><br><br><br><br><br><br><br><br><br><br>
                                        <div class="card-body text-center">


                                            <button type="submit"
                                                    style="font-size: 12px; border-radius: 20px;"
                                                    class="btn btn-primary btn-block">Guardar
                                            </button>

                                            <a href="/telefono/{{ $data['cliente'][0]->id_cliente }}"
                                               style="font-size: 12px; border-radius: 20px;" target="_blank"
                                               class="btn btn-primary btn-block">Gestionar teléfono</a>

                                            <a href="/gestion/{{ $data['cliente'][0]->id_cliente }}"
                                               style="font-size: 12px; border-radius: 20px;"
                                               class="btn btn-secondary btn-block">Regresar</a>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </form>


                </div>

                <script src="{{asset('clockpicker/assets/js/jquery.min.js')}}"></script>
                <script src="{{asset('clockpicker/assets/js/bootstrap.min.js')}}"></script>
                <script src="{{asset('clockpicker/dist/bootstrap-clockpicker.min.js')}}"></script>

                <script type="text/javascript">
                    $('.clockpicker').clockpicker()
                        .find('input').change(function () {
                        console.log(this.value);
                    });
                    var input = $('#single-input').clockpicker({
                        placement: 'bottom',
                        align: 'left',
                        autoclose: true,
                        'default': 'now'
                    });

                    $('.clockpicker-with-callbacks').clockpicker({
                        donetext: 'Done',
                        init: function () {
                            console.log("colorpicker initiated");
                        },
                        beforeShow: function () {
                            console.log("before show");
                        },
                        afterShow: function () {
                            console.log("after show");
                        },
                        beforeHide: function () {
                            console.log("before hide");
                        },
                        afterHide: function () {
                            console.log("after hide");
                        },
                        beforeHourSelect: function () {
                            console.log("before hour selected");
                        },
                        afterHourSelect: function () {
                            console.log("after hour selected");
                        },
                        beforeDone: function () {
                            console.log("before done");
                        },
                        afterDone: function () {
                            console.log("after done");
                        }
                    })
                        .find('input').change(function () {
                        console.log(this.value);
                    });

                    // Manually toggle to the minutes view
                    $('#check-minutes').click(function (e) {
                        // Have to stop propagation here
                        e.stopPropagation();
                        input.clockpicker('show')
                            .clockpicker('toggleView', 'minutes');
                    });
                    if (/mobile/i.test(navigator.userAgent)) {
                        $('input').prop('readOnly', true);
                    }
                </script>
                <script type="text/javascript">
                    function mostrar() {
                        document.getElementById('tieneConvenio').style.display = 'block';
                        document.getElementById("fechaPagoInicial").required = true;
                        document.getElementById("pagoInicial").required = true;
                        document.getElementById("select").required = true;
                        document.getElementById("deudaTotal").required = true;

                    }

                    function ocultar() {
                        document.getElementById('tieneConvenio').style.display = 'none';
                        document.getElementById("fechaPagoInicial").required = false;
                        document.getElementById("pagoInicial").required = false;
                        document.getElementById("select").required = false;
                        document.getElementById("deudaTotal").required = false;
                    }

                    function validarUnicoPago() {

                        const select = document.getElementById('select').value;
                        const pagoInicial = document.getElementById('pagoInicial').value;
                        const deudaTotal = document.getElementById('deudaTotal').value;
                        var flag = true;

                        if (select == 0) {
                            if (pagoInicial !== deudaTotal) {
                                document.getElementById("mensajeError").innerHTML = "Al seleccionar \"Un solo pago\" el PAGO INICIAL y DEUDA TOTAL deben ser la misma cantidad";
                                flag = false;
                            }
                        }


                        if (!flag) {
                            return false;
                            /*let x = document.getElementsByTagName("form");
                            x[0].submit();// Form submission*/
                        }

                    }

                    function ocultarAval() {
                        const tit_Av = document.getElementById('tit_Av').value;
                        if (tit_Av == 1) {
                            document.getElementById("idGest").required = false;
                            document.getElementById('gest').style.display = 'none';
                        } else {
                            document.getElementById('gest').style.display = 'block';
                            document.getElementById("idGest").required = true;
                        }
                    }


                </script>
                <script src="{{asset('clockpicker/assets/js/highlight.min.js')}}"></script>

            </div>
        </body>
        <footer>
            <div class="container text-center">
                <small>Copyright &copy; Sistema de Gestión y Cobranza de Cartera Vencida</small>
                <p style="font-size: 6px;">
                    Tecmor
                </p>
                <p style="font-size: 6px; visibility: hidden">
                    Desarrollador y diseñador: Alfredo Castañeda Porcayo <br>
                    Desarrollador: Felipe Guadarrama Herrera
                </p>
            </div>
        </footer>
</html>


























