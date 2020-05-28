@extends('layouts.app')
@section('title','Home')
@section('content')

    <style>
        .container {
            max-width: 1140px
        }

        .zoom:hover {
            transform: scale(1.3); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        }
        a:hover, a:visited, a:link, a:active
        {
            text-decoration: none;
        }
    </style>

    <div id="app" class="container">

        <div class="modal fade" id="bienvenido">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="margin: 0 auto;">Bienvenido</h5>
                    </div>
                    <div class="modal-body" style="margin: 0 auto;">
                        <img src="{{asset('imagesTecmor/logoTecmor.png')}}"
                             style="height: 115px; width: 400px; margin: 0 auto;">
                    </div>
                </div>
            </div>
        </div>

        <h1 class="text-center text-secondary">Inicio</h1>

        @guest  //No ha iniciado sesion
        @if (session('msj'))
            <input id="Idbienvenido" type="hidden" value="0">
        @endif
        @else
            @if (session('msj'))
                <input id="Idbienvenido" type="hidden" value="1">
            @endif



            <div class="shadow-lg card border-primary" style="border-radius: 20px; padding: 80px; margin: 100px">
                @if (Auth::user()->tipo != 'Superadministrador')
                @if (session('msjPago'))
                    <div class="alert alert-danger shadow-lg" role="alert" style="margin: 0 auto;">
                        <h6>{{session('msjPago')}}</h6>
                    </div><br><br><br>
                @endif
                @endif
                <div class="row">

                    @if (Auth::user()->tipo == 'Administrador')
                        <a href="gestionUsuario">
                            <div class="card border-0 text-center zoom" style="margin: 20px;">
                                <img class="card-img-top" src="{{asset('imagesTecmor/GestionDeUsuarios.png')}}"
                                     style="height: 180px; width: 180px; ">
                                <div class="card-body">
                                    <h6 class="card-text">Gestión de usuarios</h6>
                                </div>
                            </div>
                        </a>
                    @endif

                    @if (!(Auth::user()->tipo == 'Superadministrador'))
                        <a href="gestion">
                            <div class="card border-0 text-center zoom" style="margin: 20px;">
                                <img class="card-img-top" src="{{asset('imagesTecmor/Gestiones.png')}}"
                                     style="height: 180px; width: 180px; ">
                                <div class="card-body">
                                    <h6 class="card-text">Gestiones</h6>
                                </div>
                            </div>
                        </a>
                    @endif

                    @if (Auth::user()->tipo == 'Administrador')
                        <a href="baseDatos">
                            <div class="card border-0 text-center zoom" style="margin: 20px;">
                                <img class="card-img-top" src="{{asset('imagesTecmor/BaseDeDatos.png')}}"
                                     style="height: 180px; width: 180px; ">
                                <div class="card-body">
                                    <h6 class="card-text">Base de datos</h6>
                                </div>
                            </div>
                        </a>
                    @endif

                    @if (!(Auth::user()->tipo == 'Gestor'|| Auth::user()->tipo == 'Superadministrador'))
                        <a href="reporte">
                            <div class="card border-0 text-center zoom" style="margin: 20px;">
                                <img class="card-img-top" src="{{asset('imagesTecmor/reporte.png')}}"
                                     style="height: 180px; width: 180px; ">
                                <div class="card-body">
                                    <h6 class="card-text">Reportes</h6>
                                </div>
                            </div>
                        </a>
                    @endif


                    @if (Auth::user()->tipo == 'Administrador' || Auth::user()->tipo == 'Supervisor')
                        <a href="estadistica">
                            <div class="card border-0 text-center zoom" style="margin: 20px;">
                                <img class="card-img-top" src="{{asset('imagesTecmor/estadistica.png')}}"
                                     style="height: 180px; width: 180px; ">
                                <div class="card-body">
                                    <h6 class="card-text">Estadística</h6>
                                </div>
                            </div>
                        </a>
                    @endif

                    @if (!(Auth::user()->tipo == 'Superadministrador'))
                        <a href="calendario">
                            <div class="card border-0 text-center zoom" style="margin: 20px;">
                                <img class="card-img-top" src="{{asset('imagesTecmor/Calendario.png')}}"
                                     style="height: 180px; width: 180px; ">
                                <div class="card-body">
                                    <h6 class="card-text">Calendario</h6>
                                </div>
                            </div>
                        </a>


                        <a href="documentacion">
                            <div class="card border-0 text-center zoom" style="margin: 20px;">
                                <img class="card-img-top" src="{{asset('imagesTecmor/Documentacion.png')}}"
                                     style="height: 180px; width: 180px; ">
                                <div class="card-body">
                                    <h6 class="card-text">Documentación</h6>
                                </div>
                            </div>
                        </a>
                    @endif

                    @if (!(Auth::user()->tipo == 'Gestor'|| Auth::user()->tipo == 'Superadministrador'))
                        <a href="escaneo">
                            <div class="card border-0 text-center zoom" style="margin: 20px;">
                                <img class="card-img-top" src="{{asset('imagesTecmor/EscaneoDeCartas.png')}}"
                                     style="height: 180px; width: 180px; ">
                                <div class="card-body">
                                    <h6 class="card-text">Escaneo de cartas</h6>
                                </div>
                            </div>
                        </a>
                    @endif


                    @if (Auth::user()->tipo == 'Superadministrador')
                        <a href="/register">
                            <div class="card border-0 text-center zoom" style="margin: 20px;">
                                <img class="card-img-top" src="{{asset('imagesTecmor/AgregarUsuario.png')}}"
                                     style="height: 180px; width: 180px; ">
                                <div class="card-body">
                                    <h6 class="card-text">Gestionar usuarios</h6>
                                </div>
                            </div>
                        </a>

                        <a href="/despacho">
                            <div class="card border-0 text-center zoom" style="margin: 20px;">
                                <img class="card-img-top" src="{{asset('imagesTecmor/AgregarDespacho.png')}}"
                                     style="height: 180px; width: 180px; ">
                                <div class="card-body">
                                    <h6 class="card-text">Gestionar despacho</h6>
                                </div>
                            </div>
                        </a>
                    @endif
                    <cerrar-sesion/>
                </div>
            </div>

    </div>
    @endguest


    </div>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const Idbienvenido = document.getElementById('Idbienvenido').value;
            if (Idbienvenido == 1) {
                $('#bienvenido').modal('show');
                setTimeout(function () {
                    $('#bienvenido').modal('hide');
                }, 1500);
            }
        });
    </script>
@endsection
