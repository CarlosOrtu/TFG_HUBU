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

    <!-- Alertas -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>


    <!-- Tablas con funcionalidades-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

    <!-- Styles -->
    <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/estilo.css?v=0.1') }}" rel="stylesheet">
</head>
<body>
  <div id="app">
    @auth
    <nav class="navbar-hubu border navbar rounded navbar-expand-md navbar-light mt-4 mx-4 shadow-sm">
      <div class="container mx-0">
        <ul class="navbar-nav mr-auto">
          <a id="HUBU" data-phonetext="HUBU" data-desktoptext="Hospitar universitario de Burgos" class="navbar-brand text-white" href="{{ route('pacientes') }}"></a>
        </ul>
        <ul class="navbar-nav ml-auto justify-content-end pr-5">
          <li class="nav-item dropdown">
            <a class="nav-link text-white dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->nombre }}</a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="{{ route('pacientes') }}">Pacientes</a>
              @if(Auth::user()->id_rol == 1  )
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('usuarios') }}">Gestionar usuarios</a>
              @endif
              <div class="dropdown-divider"></div>  
              <a class="dropdown-item" href="{{ route('datospersonales') }}">Datos personales</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('logout') }}">Desconectar</a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    @if (Request::url() == route('pacientes') or Request::url() == route('nuevopaciente') or Request::url() == route('vereliminarpaciente'))
    <div class="row mx-4 mt-4">
      @include('layouts.sidebarpacientes')
      <main class="col-md-9">
        @yield('content')
      </main>
    </div>
    @elseif(Request::url() == route('usuarios') or Request::url() == route('nuevousuario') or Route::currentRouteName() == 'modificarusuario')
    <div class="row mx-4 mt-4">
      @include('layouts.sidebarusuarios')
      <main class="col-md-9">
        @yield('content')
      </main>
    </div>     
    @elseif(Request::url() == route('datospersonales') or Request::url() == route('modificarcontrasena'))
    <div class="row mx-4 mt-4"> 
      @include('layouts.sidebardatospersonales')
      <main class="col-md-9">
        @yield('content')
      </main>
    </div>    
    @else
    <div class="row mx-4 mt-4">
      @include('layouts.sidebarenfermedad')  
      <main class="col-md-9">
          @yield('content')
      </main>
    </div>
    </div>
    @endif
    @endauth
    @guest
    <main id="login">
      @yield('content')
    </main>
    @endguest
  </div>
  <script type="text/javascript">
    var url = $(location).attr('pathname');
    switch (true){
      case /enfermedad/.test(url):
        $('#menu2').addClass("show");
        $('#boton2').attr("aria-expanded","true");
        switch(true){
          case /datosgenerales/.test(url):
            $('#datosgenerales').focus();
            break;
          case /sintomas/.test(url):
            $('#sintomas').focus();
            break;
          case /metastasis/.test(url):
            $('#metastasis').focus();
            break;
          case /biomarcadores/.test(url):
            $('#biomarcadores').focus();
            break;
          case /pruebas/.test(url):
            $('#pruebas').focus();
            break;
          case /tecnicas/.test(url):
            $('#tecnicas').focus();
            break;
          case /otrostumores/.test(url):
            $('#otrostumores').focus();
            break;
        }
        break;
      case /antecedentes/.test(url):
        $('#menu3').addClass("show");
        $('#boton3').attr("aria-expanded","true"); 
        switch(true){
          case /medicos/.test(url):
            $('#antecedentes_medicos').focus();
            break;
          case /oncologicos/.test(url):
            $('#antecedentes_oncologicos').focus();
            break;
          case /familiares/.test(url):
            $('#antecedentes_familiares').focus();
            break;
        }
        break;
      case /tratamientos/.test(url):
        $('#menu4').addClass("show");
        $('#boton4').attr("aria-expanded","true"); 
        switch(true){
          case /quimioterapia/.test(url):
            $('#quimioterapia').focus();
            break;
          case /radioterapia/.test(url):
            $('#radioterapia').focus();
            break;
          case /cirugia/.test(url):
            $('#cirugia').focus();
            break;
        }
        break;
      case /reevaluaciones/.test(url):
        $('#menu5').addClass("show");
        $('#boton5').attr("aria-expanded","true"); 
        break;
      case /seguimientos/.test(url):
        $('#menu6').addClass("show");
        $('#boton6').attr("aria-expanded","true"); 
        break;
      case /comentarios/.test(url):
        $('#menu7').addClass("show");
        $('#boton7').attr("aria-expanded","true"); 
        break;
      default: 
        $('#menu1').addClass("show");
        $('#boton1').attr("aria-expanded","true"); 
        break; 
    }
    $(document).ready(function(){
        $(".btn.btn-toggle.text-white").click(function(event) {
            var id = $(this).attr('id');
            $('.btn.btn-toggle.text-white').each(function(){
              if(id != $(this).attr('id')){
                console.log("Funciona");
                $($(this).attr("data-target")).collapse('hide');
              }
            });
        });
    });
  </script>
</body>
</html>
