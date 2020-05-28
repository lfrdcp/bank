@extends('layouts.appSinJava')
@section('title','Iniciar sesion')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
@section('content')
    <body>
    <div class="bg-img">
        <div class="containeer">
            <img class="img-fluid animated  slow slideInRight" src="{{asset('imagesTecmor/LogoTecmor.jp2')}}"
                 style="width:70%;height:auto;">
        </div>
    </div>
    <div class="container">
        @if(!empty($msj))
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">NOTA</strong>
                    </div>
                    <div class="card-body ">
                        <p class="card-text ">
                            {{ $msj }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <p class="animated  slow slideInRight display-4 text-center text-secondary" style="font-size: 45px;"><b>Sistema de gestión y cobranza de cartera vencida</b></p>


        <div class="col-12 col-sm-12 col-md-5 col-lg-6 col-xl-6 offset-md-0">
            <div class="card-transparent shadow-lg animated slow  slideInLeft" style="  margin: 8%">
                <div class="card-body">
                    <div class="card-title">
                        <p class="aligncenter">
                            <img class="card-img-top img-fluid" src="{{asset('imagesTecmor/login.gif')}}"
                                 style="width: 18%;height:auto;">
                        <h6 class="display-4 text-center text-secondary" style="font-size: 30px;">Iniciar sesión</h6>

                    </div>
                    <br>
                    <div class="col">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group form-control-sm row">
                                <img class="img-fluid " src="{{asset('imagesTecmor/name.jp2')}}"
                                     style="width: 40px;  height:40px;">
                                <div class="col">
                                    <input id="login" placeholder="Usuario" type="login"
                                           class="form-control form-control-sm" @error('login') is-invalid @enderror
                                           name="login" value="{{ old('login') }}" required autofocus
                                    >
                                    @error('login')
                                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                                    @enderror
                                </div>
                            </div>
                            <br>
                            <div class="form-group form-control-sm row ">
                                <img class="img-fluid " src="{{asset('imagesTecmor/password.jp2')}}"
                                     style="width: 40px;  height:40px;">
                                <div class="col">
                                    <input placeholder="Contraseña" id="password" type="password"
                                           class="form-control form-control-sm " @error('password') is-invalid
                                           @enderror name="password" required autocomplete="current-password"
                                    >
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                                    @enderror
                                </div>
                            </div>
                            <br><br>
                            <button type="submit" class="btn btn-primary btn-block "
                            >
                                Iniciar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>



    </div>

    <br>
    </body>

    <style>
        .aligncenter {
            text-align: center;
        }

        /*  .card {
              background: linear-gradient(to top,#FFFFFF,#999999) no-repeat center center fixed;
          }*/

        .container {
            max-width: 1140px
        }


    </style>

    <style>
        body {
            background-image: url("imagesTecmor/backgorund.jpg");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .bg-img {

            min-height: 230px;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
        }

        .containeer {
            position: absolute;
            margin: 65px;
            width: auto;
        }
    </style>

@endsection
