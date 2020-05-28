@extends('layouts.app')

@section('content')
    <div  class="container">
        @include('includes.barraMenu')

        <h1 class="text-center text-secondary" style="font-size: 25px; font-family: 'Century Gothic'">Gestionar usuarios</h1>

        <div class="shadow-lg card border-primary" style="border-radius: 20px; margin: 15px;">
            <div class="card-body">

                <div style="display: table; margin: 0 auto;">
                    <usuario-show/>
                </div>
                <br>
                <hr>


                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group row">
                        <label for="tipo" class="col-md-4 col-form-label text-md-right">Tipo de usuario</label>
                        <div class="col-auto">
                            <select  class="border-primary custom-select mr-sm-2"
                                    name="tipo" id="tipo" type="text" onclick="ocultarDespacho()"
                                    class="form-control @error('tipo') is-invalid @enderror " name="tipo" required
                                    autocomplete="tipo">
                                <option selected></option>
                                <option value="Superadministrador">Superadministrador</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Supervisor">Supervisor</option>
                                <option value="Gestor">Gestor</option>
                            </select>

                            @error('tipo')
                            <span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
                            @enderror
                        </div>
                    </div>

                    <div id="despacho">
                        <div class="form-group row">
                            <label for="despacho" class="col-md-4 col-form-label text-md-right">Despacho</label>
                            <div class="col-auto">
                                <select  class=" border-primary custom-select mr-sm-2"
                                        name="despacho" type="text" id="despachoSelect"
                                        class="form-control @error('despacho') is-invalid @enderror " name="despacho"
                                        autocomplete="despacho">
                                    <option selected></option>
                                    @for($i=0;$i<count($data);$i++)
                                        <option value="{{$data[$i]->nombre}}">{{$data[$i]->nombre}}</option>
                                    @endfor
                                </select>
                                @error('despacho')
                                <span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Nombre</label>

                        <div class="col-md-6">
                            <input  id="name" type="text"
                                   class="form-control @error('name') is-invalid @enderror border-primary"
                                   name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="last_name" class="col-md-4 col-form-label text-md-right">Apellido</label>

                        <div class="col-md-6">
                            <input  id="last_name" type="text"
                                   class="form-control @error('last_name') is-invalid @enderror border-primary"
                                   name="last_name"
                                   value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>

                            @error('last_name')
                            <span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="username" class="col-md-4 col-form-label text-md-right">Nombre de Usuario</label>

                        <div class="col-md-6">
                            <input  id="username" type="text"
                                   class="form-control @error('username') is-invalid @enderror border-primary"
                                   name="username"
                                   value="{{ old('username') }}" required autocomplete="username" autofocus>

                            @error('username')
                            <span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail</label>

                        <div class="col-md-6">
                            <input  id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror border-primary"
                                    name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Contraseña</label>

                        <div class="col-md-6">
                            <input  id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror border-primary"
                                   name="password" required
                                   autocomplete="new-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirmar
                            contraseña</label>

                        <div class="col-md-6">
                            <input  id="password-confirm" type="password"
                                   class="form-control border-primary"
                                   name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit"
                                    class="btn btn-block btn-primary">
                                Registrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style>
        .container {
            max-width:1140px
        }
    </style>

    <script>
        function ocultarDespacho() {
            const tipo = document.getElementById('tipo').value;
            if (tipo === "Superadministrador") {
                document.getElementById('despacho').style.display = 'none';
                document.getElementById("despachoSelect").required = false;
            } else {
                document.getElementById('despacho').style.display = 'block';
                document.getElementById("despachoSelect").required = true;
            }
        }
    </script>
@endsection
