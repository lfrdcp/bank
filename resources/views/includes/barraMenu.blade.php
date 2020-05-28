<nav class="navbar navbar-expand-lg navbar-light ">

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item" style="margin: 28px;">
                <a href="/home">
                    <img class="mx-auto d-block " src="{{asset('imagesTecmor/home.jp2')}}"
                         style="height: 50px; width: 50px; ">
                    <h6 class="text-center" style="font-size: 12px; font-family: 'Century Gothic'">Inicio</h6>
                </a>
            </li>

            @if (Auth::user()->tipo == 'Administrador')
                <li class="nav-item" style="margin: 28px;">
                    <a href="/gestiones_masivas">
                        <img class="mx-auto d-block " src="{{asset('imagesTecmor/gestiones_masivas.jp2')}}"
                             style="height: 50px; width: 50px; ">
                        <h6 class="text-center" style="font-size: 12px; font-family: 'Century Gothic'">Gestiones <br>masivas</h6>
                    </a>
                </li>
            @endif


            @if (!(Auth::user()->tipo == 'Superadministrador'))
                <li class="nav-item" style="margin: 28px;">
                    <a href="/gestion">
                        <img class="mx-auto d-block " src="{{asset('imagesTecmor/Gestiones.jp2')}}"
                             style="height: 50px; width: 50px; ">
                        <h6 class="text-center" style="font-size: 12px; font-family: 'Century Gothic'">Gestiones</h6>

                    </a>
                </li>
            @endif


            @if (Auth::user()->tipo == 'Administrador')
                <li class="nav-item" style="margin: 28px;">
                    <a href="/gestionar_usuarios">
                        <img class="mx-auto d-block " src="{{asset('imagesTecmor/AgregarUsuario.jp2')}}"
                             style="height: 50px; width: 50px; ">
                        <h6 class="text-center" style="font-size: 12px; font-family: 'Century Gothic'">Gestionar <br>usuarios</h6>
                    </a>
                </li>
            @endif


            @if (Auth::user()->tipo == 'Administrador')
                <li class="nav-item" style="margin: 28px;">
                    <a href="/base_datos">
                        <img class="mx-auto d-block " src="{{asset('imagesTecmor/BaseDeDatos.jp2')}}"
                             style="height: 50px; width: 50px; ">
                        <h6 class="text-center" style="font-size: 12px; font-family: 'Century Gothic'">Base de <br>datos</h6>
                    </a>
                </li>
            @endif


            @if (!(Auth::user()->tipo == 'Gestor'|| Auth::user()->tipo == 'Superadministrador'))
                <li class="nav-item" style="margin: 28px;">
                    <a href="/reporte">
                        <img class="mx-auto d-block " src="{{asset('imagesTecmor/reporte.jp2')}}"
                             style="height: 50px; width: 50px; ">
                        <h6 class="text-center" style="font-size: 12px; font-family: 'Century Gothic'">Reportes</h6>
                    </a>
                </li>
            @endif


            @if (Auth::user()->tipo == 'Administrador' || Auth::user()->tipo == 'Supervisor')
                <li class="nav-item" style="margin: 28px;">
                    <a href="/estadistica">
                        <img class="mx-auto d-block " src="{{asset('imagesTecmor/estadistica.jp2')}}"
                             style="height: 50px; width: 50px; ">
                        <h6 class="text-center" style="font-size: 12px; font-family: 'Century Gothic'">Estadística</h6>

                    </a>
                </li>
            @endif



            @if (!(Auth::user()->tipo == 'Superadministrador'))
                <li class="nav-item" style="margin: 28px;">
                    <a href="/calendario">
                        <img class="mx-auto d-block " src="{{asset('imagesTecmor/Calendario.jp2')}}"
                             style="height: 50px; width: 50px; ">
                        <h6 class="text-center" style="font-size: 12px; font-family: 'Century Gothic'">Calendario</h6>
                    </a>
                </li>
            @endif


            @if (!(Auth::user()->tipo == 'Superadministrador'))
                <li class="nav-item" style="margin: 28px;">
                    <a href="/documentacion">
                        <img class="mx-auto d-block " src="{{asset('imagesTecmor/Documentacion.jp2')}}"
                             style="height: 50px; width: 50px; ">
                        <h6 class="text-center" style="font-size: 12px; font-family: 'Century Gothic'">Documentación</h6>
                    </a>
                </li>
            @endif

            @if (Auth::user()->tipo == 'Superadministrador')
                <li class="nav-item" style="margin: 28px;">
                    <a href="/register">
                        <img class="mx-auto d-block " src="{{asset('imagesTecmor/AgregarUsuario.jp2')}}"
                             style="height: 50px; width: 50px; ">
                        <h6 class="text-center" style="font-size: 12px; font-family: 'Century Gothic'">Gestionar <br> usuarios</h6>
                    </a>
                </li>
            @endif

            @if (Auth::user()->tipo == 'Superadministrador')
                <li class="nav-item" style="margin: 28px;">
                    <a href="/despacho">
                        <img class="mx-auto d-block " src="{{asset('imagesTecmor/AgregarDespacho.jp2')}}"
                             style="height: 50px; width: 50px; ">
                        <h6 class="text-center" style="font-size: 12px; font-family: 'Century Gothic'">Gestionar <br>despachos</h6>
                    </a>
                </li>
            @endif

        </ul>
    </div>
</nav>
