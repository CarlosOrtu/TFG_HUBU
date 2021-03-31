<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <script type = "text/javascript" src="{{ asset('/js/jquery-3.6.0.min.js') }}"></script>
    <script type = "text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>



    <!-- Styles -->
    <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/estilo.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">
        @if(Auth::check())
        <nav class="border navbar rounded navbar-expand-md navbar-light bg-transparent mt-4 mx-4 shadow-sm">
            <div class="container mx-0">
                <ul class="navbar-nav mr-auto">
                        <a id="HUBU" data-phonetext="HUBU" data-desktoptext="Hospitar universitario de Burgos" class="navbar-brand text-white" href="{{ route('pacientes') }}"></a>
                </ul>
                <ul class="navbar-nav ml-auto justify-content-end pr-5">
                      <li class="nav-item dropdown">
                        <a class="nav-link text-white dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}</a>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="#">Datos personales</a>
                            <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="{{ route('logout') }}">Desconectar</a>
                        </div>
                      </li>
                </ul>
            </div>
        </nav>
        @endif
        @if (Request::url() == route('pacientes'))
        <div class="row mx-4 mt-4">
            <div class="col-md-3 pl-0">
                <ul class="navbar-nav ml-auto justify-content-end pr-5">
                <div class="border rounded p-3 bg-transparent">
                    <li class="mb-1">
                      <button class="btn btn-toggle text-white" id="boton1" data-bs-toggle="collapse" data-bs-target="#menu1" aria-expanded="false">
                        Opcion 1
                      </button>
                      <div class="collapse" id="menu1">
                        <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
                          <li><a href="#" class="text-white rounded">Opcion 1.1</a></li>
                          <li><a href="#" class="text-white rounded">Opcion 1.2</a></li>
                          <li><a href="#" class="text-white rounded">Opcion 1.3</a></li>
                        </ul>
                      </div>
                    </li>
                    <li class="mb-1">
                      <button class="btn btn-toggle text-white" id="boton2" data-bs-toggle="collapse" data-bs-target="#menu2" aria-expanded="false">
                        Opcion 2
                      </button>
                      <div class="collapse" id="menu2">
                        <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
                          <li><a href="#" class="text-white rounded">Opcion 2.1</a></li>
                          <li><a href="#" class="text-white rounded">Opcion 2.2</a></li>
                          <li><a href="#" class="text-white rounded">Opcion 2.3</a></li>
                        </ul>
                      </div>
                    </li>
                    <li class="mb-1">
                      <button class="btn btn-toggle text-white" id="boton3" data-bs-toggle="collapse" data-bs-target="#menu3" aria-expanded="false">
                        Opcion 3
                      </button>
                      <div class="collapse" id="menu3">
                        <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
                          <li><a href="#" class="rounded text-white">Opcion 3.1</a></li>
                          <li><a href="#" class="text-white rounded">Opcion 3.2</a></li>
                          <li><a href="#" class="text-white rounded">Opcion 3.3</a></li>
                        </ul>
                      </div>
                    </li>
                    <li class="mb-1">
                      <button class="btn btn-toggle text-white" id="boton4" data-bs-toggle="collapse" data-bs-target="#menu4" aria-expanded="false">
                        Opcion 4
                      </button>
                      <div class="collapse" id="menu4">
                        <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
                          <li><a href="#" class="rounded text-white">Opcion 3.1</a></li>
                          <li><a href="#" class="text-white rounded">Opcion 3.2</a></li>
                          <li><a href="#" class="text-white rounded">Opcion 3.3</a></li>
                        </ul>
                      </div>
                    </li>
                    <li class="mb-1">
                      <button class="btn btn-toggle text-white" id="boton5" data-bs-toggle="collapse" data-bs-target="#menu5" aria-expanded="false">
                        Opcion 5
                      </button>
                      <div class="collapse" id="menu5">
                        <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
                          <li><a href="#" class="rounded text-white">Opcion 3.1</a></li>
                          <li><a href="#" class="text-white rounded">Opcion 3.2</a></li>
                          <li><a href="#" class="text-white rounded">Opcion 3.3</a></li>
                        </ul>
                      </div>
                    </li>
                    <li class="mb-1">
                      <button class="btn btn-toggle text-white" id="boton6" data-bs-toggle="collapse" data-bs-target="#menu6" aria-expanded="false">
                        Opcion 6
                      </button>
                      <div class="collapse" id="menu6">
                        <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
                          <li><a href="#" class="rounded text-white">Opcion 3.1</a></li>
                          <li><a href="#" class="text-white rounded">Opcion 3.2</a></li>
                          <li><a href="#" class="text-white rounded">Opcion 3.3</a></li>
                        </ul>
                      </div>
                    </li>
                    <li class="mb-1">
                      <button class="btn btn-toggle text-white" id="boton7" data-bs-toggle="collapse" data-bs-target="#menu7" aria-expanded="false">
                        Opcion 7
                      </button>
                      <div class="collapse" id="menu7">
                        <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
                          <li><a href="#" class="rounded text-white">Opcion 3.1</a></li>
                          <li><a href="#" class="text-white rounded">Opcion 3.2</a></li>
                          <li><a href="#" class="text-white rounded">Opcion 3.3</a></li>
                        </ul>
                      </div>
                    </li>
                    <li class="mb-1">
                      <button class="btn btn-toggle text-white" id="boton8" data-bs-toggle="collapse" data-bs-target="#menu8" aria-expanded="false">
                        Opcion 8
                      </button>
                      <div class="collapse" id="menu8">
                        <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
                          <li><a href="#" class="rounded text-white">Opcion 3.1</a></li>
                          <li><a href="#" class="text-white rounded">Opcion 3.2</a></li>
                          <li><a href="#" class="text-white rounded">Opcion 3.3</a></li>
                        </ul>
                      </div>
                    </li>
                  </ul>
                </div>
            </div>
        </div>
        @endif
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".btn.btn-toggle.text-white").click(function(event) {

                let id = "#" + this.id;
                var menu = $(id).attr("data-bs-target");
                if( $(menu).hasClass("show") ) {
                    $(menu).removeClass("show");
                }else{
                    $(menu).addClass("show");
                }
                if( $(id).attr("aria-expanded") == "true" ){
                    $(id).attr("aria-expanded","false");
                }else{
                    $(id).attr("aria-expanded","true");
                }
            });
        });
    </script>
</body>
</html>
