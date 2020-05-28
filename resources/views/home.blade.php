@extends('layouts.app')
@section('title','Home')
@section('content')
    <div class="container">

        <div class="modal fade" id="bienvenido">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="margin: 0 auto;">Bienvenido</h5>
                    </div>
                    <div class="modal-body" style="margin: 0 auto;">
                        <img src="{{asset('imagesTecmor/logoTecmor.jp2')}}"
                             style="height: 115px; width: 400px; margin: 0 auto;">
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-0" style="border-radius: 20px; margin: 15px;">
            <div class="card-title">
                <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">
                    Inicio</h1>
            </div>
        </div>


        @guest  //No ha iniciado sesion
        @if (session('msj'))
            <input id="Idbienvenido" type="hidden" value="0">
        @endif

        @else
            @if (session('msj'))
                <input id="Idbienvenido" type="hidden" value="1">
                <div class="alert alert-secondary" role="alert">
                    <h3>{{session('msj')}}</h3>
                </div>
            @endif
            <div class="card-transparent shadow-lg center"
                 style="border-radius: 20px; margin-top: 20px;">
                <div class="card-columns">

                    @if ((Auth::user()->tipo == 'Administrador') || (Auth::user()->tipo == 'Supervisor'))
                        <div class="card border-0 col-sm center text-center  "
                             style="background-color: transparent; margin-top: 5%">
                            <a href="reporte">
                                <img class="card-img-top" src="{{asset('imagesTecmor/reporte.jp2')}}"
                                     style="width: 55%; height: auto;">
                                <div class="card-body">
                                    <h5 class="card-text col-sm" style="font-family: 'Century Gothic'">Reportes</h5>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if ((Auth::user()->tipo == 'Administrador'))
                        <div class="card border-0 col-sm center text-center  "
                             style="background-color: transparent; margin-top: 5%">
                            <a href="/gestionar_usuarios">
                                <img class="card-img-top" src="{{asset('imagesTecmor/AgregarUsuario.jp2')}}"
                                     style="width: 55%; height: auto;">
                                <div class="card-body">
                                    <h5 class="card-text col-sm" style="font-family: 'Century Gothic'">Gestionar
                                        usuarios</h5>
                                </div>
                            </a>
                        </div>
                    @endif

                    @if ((Auth::user()->tipo == 'Administrador'))
                        <div class="card border-0 col-sm center text-center  "
                             style="background-color: transparent; margin-top: 5%"><a href="base_datos">
                                <img class="card-img-top" src="{{asset('imagesTecmor/BaseDeDatos.jp2')}}"
                                     style="width: 55%; height: auto;">
                                <div class="card-body">
                                    <h5 class="card-text col-sm" style="font-family: 'Century Gothic'">Base de
                                        datos</h5>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if (!(Auth::user()->tipo == 'Superadministrador'))
                        <div class="card border-0 col-sm center text-center  "
                             style="background-color: transparent; margin-top: 5%">
                            <a href="gestion">
                                <img class="card-img-top" src="{{asset('imagesTecmor/Gestiones.jp2')}}"
                                     style="width: 55%; height: auto;">
                                <div class="card-body">
                                    <h5 class="card-text col-sm" style="font-family: 'Century Gothic'">Gestiones</h5>
                                </div>
                            </a>
                        </div>
                    @endif

                    @if ((Auth::user()->tipo == 'Administrador'))
                        <div class="card border-0 col-sm center text-center  "
                             style="background-color: transparent; margin-top: 5%">
                            <a href="gestiones_masivas">
                                <img class="card-img-top" src="{{asset('imagesTecmor/gestiones_masivas.jp2')}}"
                                     style="width: 55%; height: auto;">
                                <div class="card-body">
                                    <h5 class="card-text col-sm" style="font-family: 'Century Gothic'">Gestiones
                                        masivas</h5>
                                </div>
                            </a>
                        </div>
                    @endif


                    @if (!(Auth::user()->tipo == 'Superadministrador'))
                        <div class="card border-0 col-sm center text-center  "
                             style="background-color: transparent; margin-top: 5%">
                            <a href="documentacion">
                                <img class="card-img-top" src="{{asset('imagesTecmor/Documentacion.jp2')}}"
                                     style="width: 55%; height: auto;">
                                <div class="card-body">
                                    <h5 class="card-text col-sm" style="font-family: 'Century Gothic'">
                                        Documentación</h5>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if (!(Auth::user()->tipo == 'Superadministrador'))
                        <div class="card border-0 col-sm center text-center  "
                             style="background-color: transparent; margin-top: 5%">
                            <a href="calendario">
                                <img class="card-img-top" src="{{asset('imagesTecmor/Calendario.jp2')}}"
                                     style="width: 55%; height: auto;">
                                <div class="card-body">
                                    <h5 class="card-text col-sm" style="font-family: 'Century Gothic'">Calendario</h5>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if (Auth::user()->tipo == 'Administrador' || Auth::user()->tipo == 'Supervisor')
                        <div class="card border-0 col-sm center text-center  "
                             style="background-color: transparent; margin-top: 5%">
                            <a href="estadistica">
                                <img class="card-img-top" src="{{asset('imagesTecmor/estadistica.jp2')}}"
                                     style="width: 55%; height: auto;">
                                <div class="card-body">
                                    <h5 class="card-text col-sm" style="font-family: 'Century Gothic'">Estadística</h5>
                                </div>
                            </a>
                        </div>
                    @endif

                    @if (Auth::user()->tipo == 'Superadministrador')
                        <div class="card border-0 col-sm center text-center  "
                             style="background-color: transparent; margin-top: 5%">
                            <a href="/register">
                                <img class="card-img-top" src="{{asset('imagesTecmor/AgregarUsuario.jp2')}}"
                                     style="width: 55%; height: auto;">
                                <div class="card-body">
                                    <h5 class="card-text col-sm" style="font-family: 'Century Gothic'">Gestionar
                                        usuarios</h5>
                                </div>
                            </a>
                        </div>
                        <div class="card border-0 col-sm center text-center  "
                             style="background-color: transparent; margin-top: 5%">
                            <a href="/despacho">
                                <img class="card-img-top" src="{{asset('imagesTecmor/AgregarDespacho.jp2')}}"
                                     style="width: 55%; height: auto;">
                                <div class="card-body">
                                    <h5 class="card-text col-sm" style="font-family: 'Century Gothic'">Gestionar
                                        despacho</h5>
                                </div>
                            </a>
                        </div>
                    @endif
                    <div>
                        <div class="card border-0 col-sm center text-center  "
                             style="background-color: transparent; margin-top: 5%">
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <img class="card-img-top" src="{{asset('imagesTecmor/CerrarSesion.jp2')}}"
                                     style="width: 55%; height: auto;">
                                <div class="card-body">
                                    <h5 class="card-text col-sm" style="font-family: 'Century Gothic'">Cerrar
                                        Sesión</h5>
                                </div>
                            </a>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>

                </div>
            </div>
    </div>

    @endguest

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
