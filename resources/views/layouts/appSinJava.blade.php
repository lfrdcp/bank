<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tecmor - @yield('title')</title>

    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('imagesTecmor/logofondo.jp2')}}"/>

    <script src="{{ asset('jsSinVue/app.js') }}" defer></script>
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
    @include('includes.fondo_colores')
</head>
@if(Auth::check())
    <body onload="reloj()">
    @else
        <body>
        @endif
        @if(Auth::check())
            <div id="app">
                <nav class="nav navbar navbar-expand-sm  mx-auto bg-transparent" style="margin: 50px;">
                    <div class="container col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">


                        <div class="card  border-0 col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <img class="card-img-top mx-auto" src="{{asset('imagesTecmor/logofondo.jp2')}}"
                                 style="width: 35px; height: 35px; margin: 8px;">

                            <b style="font-size: 10px; font-family: 'Century Gothic'" class="text-secondary mx-auto">Sistema
                                de gestión y
                                cobranza de cartera vencida</b>
                        </div>


                        <div class="card border-0 col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <img class="card-img-top mx-auto" src="{{asset('imagesTecmor/cal-hor.jp2')}}"
                                 style="width: 35px; height: 35px; margin: 8px;">

                            <b style="font-size: 10px; font-family: 'Century Gothic'"
                               class="text-secondary mx-auto">{{$arrayDate['weekday'] }} {{ date ("j") }}
                                de {{ $arrayDate['mes'] }}
                                del {{ date ("Y") }}</b>

                            <b style="font-size: 10px; font-family: 'Century Gothic'" class="text-secondary mx-auto">Semana: {{ $arrayDate['dt'] }}
                                | Hora:</b>
                            <b id="print" style="font-size: 10px; font-family: 'Century Gothic'"
                               class="text-secondary mx-auto"></b>

                        </div>

                        <div class="card border-0 col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <img class="card-img-top mx-auto" src="{{asset('imagesTecmor/name.jp2')}}"
                                 style="width: 35px; height: 35px; margin: 8px;">
                            <b style="font-size: 10px; font-family: 'Century Gothic'" class="text-secondary mx-auto">
                                Bienvenido {{ Auth::user()->tipo }}:</b>
                            <b style="font-size: 10px; font-family: 'Century Gothic'"
                               class="text-secondary mx-auto">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</b>
                        </div>

                    </div>
                </nav>
                @endif

                @yield('content')
                @csrf
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

