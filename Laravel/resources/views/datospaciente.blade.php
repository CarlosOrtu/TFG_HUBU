@extends('layouts.app')

@section('content')
<form action="{{ route('datospaciente', ['id' => $paciente->id_paciente]) }}" method="post">
    @CSRF
    @method('put')
    <div class="col-md-11 pl-0">
        <div class="d-flex justify-content-between mb-4">
            <h6 class="align-self-end text-white">Paciente: {{ $paciente->nombre }}</h6>
            <h1 class="align-self-center text-white panel-title">Datos paciente</h1>
            <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
        </div>
        @if ($message = Session::get('SQLerror'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="text-dark close" data-dismiss="alert">x</button>
            <strong class="text-center text-dark">{{ $message }}</strong>
        </div>
        @endif
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="text-dark  close" data-dismiss="alert">x</button>
            <strong class="text-center text-dark">{{ $message }}</strong>
        </div>
        @endif
        <div class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Nombre</span>
            </div>
            <input value="{{ $paciente->nombre }}" name="nombre" class="form-control @error('nombre') is-invalid @enderror" autocomplete="off">
            @error('nombre')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror  
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Apellidos</span>
            </div>
            <input value="{{ $paciente->apellidos }}" name="apellidos" class="form-control @error('apellidos') is-invalid @enderror" autocomplete="off">
            @error('apellidos')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror  
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Sexo</span>
            </div>
            <select name="sexo" class="form-control">
                <option {{ $paciente->sexo == 'Masculino' ? 'selected' : '' }} >Masculino</option>
                <option {{ $paciente->sexo == 'Femenino' ? 'selected' : '' }} >Femenino</option>
            </select> 
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Nacimiento</span>
            </div>
            <input value="{{ $paciente->nacimiento }}" type="date" name="nacimiento" class="form-control @error('nacimiento') is-invalid @enderror" autocomplete="off">
            @error('nacimiento')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror  
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Raza</span>
            </div>
            <select name="raza" class="form-control">
                <option {{ $paciente->raza == 'Caucásico' ? 'selected' : '' }} >Caucásico</option>
                <option {{ $paciente->raza == 'Asiático' ? 'selected' : '' }} >Asiático</option>
                <option {{ $paciente->raza == 'Africano' ? 'selected' : '' }} >Africano</option>
                <option {{ $paciente->raza == 'Latino' ? 'selected' : '' }} >Latino</option>
            </select> 
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Profesión</span>
            </div>
            <select name="profesion" class="tipo form-control">
                <option {{ $paciente->profesion == 'Construcción' ? 'selected' : '' }}>Construcción</option>
                <option {{ $paciente->profesion == 'Minería' ? 'selected' : '' }}>Minería</option>
                <option {{ $paciente->profesion == 'Pintor' ? 'selected' : '' }}>Pintor</option>
                <option {{ $paciente->profesion == 'Peluquero' ? 'selected' : '' }}>Peluquero</option>
                <option {{ $paciente->profesion == 'Industria textil' ? 'selected' : '' }}>Industria textil</option>
                <option {{ $paciente->profesion == 'Mecánico' ? 'selected' : '' }}>Mecánico</option>
                <option {{ $paciente->profesion == 'Limpieza' ? 'selected' : '' }}>Limpieza</option>
                <option {{ $paciente->profesion == 'Cerámicas' ? 'selected' : '' }}>Cerámicas</option>
                <option {{ $paciente->profesion == 'Desconocido' ? 'selected' : '' }}>Desconocido</option>
                <option {{ preg_match("/^Otro: /", $paciente->profesion) ? 'selected' : '' }}>Otro</option>
            </select>
        </div>
        <div class="oculto ml-2 my-4 input-group">
          <div class="input-group-prepend">
              <span class="input-group-text">Especificar <br>profesión</span>
          </div>
          @if(preg_match("/^Otro: /", $paciente->profesion))
          <input value="{{ substr($paciente->profesion, 6) }}" name="profesion_especificar" class="form-control" autocomplete="off">
          @else
          <input name="profesion_especificar" class="form-control" autocomplete="off">
          @endif
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Fumador</span>
            </div>
            <select name="fumador" class="tipo form-control">
                <option {{ $paciente->fumador == 'Fumador' ? 'selected' : '' }}>Fumador</option>
                <option {{ $paciente->fumador == 'Exfumador' ? 'selected' : '' }}>Exfumador</option>
                <option {{ $paciente->fumador == 'Nunca fumador' ? 'selected' : '' }}>Nunca fumador</option>
                <option {{ $paciente->fumador === null ? 'selected' : '' }}>Desconocido</option>
            </select>
        </div>
        <div class="oculto ml-2 my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Número de<br> cigarros al día</span>
            </div>
            @if($paciente->num_tabaco_dia === null)
            <input name="especificar_fumador" class="form-control" autocomplete="off">
            @else
            <input value="{{ $paciente->num_tabaco_dia }}" name="especificar_fumador" class="form-control" autocomplete="off">
            @endif
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Bebedor</span>
            </div>
            <select name="bebedor" class="form-control">
                <option {{ $paciente->bebedor == 'Bebedor' ? 'selected' : '' }}>Bebedor</option>
                <option {{ $paciente->bebedor == 'Exbebedor' ? 'selected' : '' }}>Exbebedor</option>
                <option {{ $paciente->bebedor == 'Nunca bebedor' ? 'selected' : '' }}>Nunca bebedor</option>
                <option {{ $paciente->bebedor === NULL ? 'selected' : '' }}>Desconocido</option>
            </select> 
        </div>      
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Carcinógenos</span>
            </div>
            <select name="carcinogenos" class="tipo form-control">
                <option {{ $paciente->carcinogenos == 'Asbesto' ? 'selected' : '' }}>Asbesto</option>
                <option {{ preg_match("/^Otro: /", $paciente->carcinogenos) ? 'selected' : '' }}>Otro</option>
                <option {{ $paciente->carcinogenos === null ? 'selected' : '' }}>Desconocido</option>
            </select>
        </div>   
        <div class="oculto ml-2 my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Especificar <br>carcinógeno</span>
            </div>
            @if(preg_match("/^Otro: /", $paciente->carcinogenos))
            <input value="{{ substr($paciente->carcinogenos, 6) }}" name="especificar_carcinogeno" class="form-control" autocomplete="off">
            @else
            <input name="especificar_carcinogeno" class="form-control" autocomplete="off">
            @endif 
        </div>      
        <div class="d-flex justify-content-center">
            <button id="boton_crearpaciente" type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </div>
</form>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection
