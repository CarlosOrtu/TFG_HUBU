@extends('layouts.app')
    
@section('content')
<form method="POST">
    @CSRF
    <div class="col-md-12 pl-0">
        <h1 class="text-white text-center panel-title">Añadir paciente</h1>
        @if ($message = Session::get('SQLerror'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="text-dark close" data-dismiss="alert">x</button>
            <strong class="text-center text-dark">{{ $message }}</strong>
        </div>
        @endif
        @if(env('APP_ENV') == 'production')
        <div class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">NHC</span>
            </div>
            <input name="nhc" value="{{ old('nhc') }}" class="form-control @error('nhc') is-invalid @enderror" autocomplete="off">
            @error('nhc')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        @else
        <div class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Nombre</span>
            </div>
            <input name="nombre" value="{{ old('nombre') }}" class="form-control @error('nombre') is-invalid @enderror" autocomplete="off">
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
            <input name="apellidos" value="{{ old('apellidos') }}" class="form-control @error('apellidos') is-invalid @enderror" autocomplete="off">
            @error('apellidos')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        @endif
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Sexo</span>
            </div>
            <select name="sexo" class="form-control">
                <option {{ old('sexo') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                <option {{ old('sexo') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
            </select>
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Nacimiento</span>
            </div>
            <input name="nacimiento" value="{{ old('nacimiento') }}" type="date" class="form-control @error('nacimiento') is-invalid @enderror">
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
                <option {{ old('raza') == 'Caucásico' ? 'selected' : '' }}>Caucásico</option>
                <option {{ old('raza') == 'Asiático' ? 'selected' : '' }}>Asiático</option>
                <option {{ old('raza') == 'Africano' ? 'selected' : '' }}>Africano</option>
                <option {{ old('raza') == 'Latino' ? 'selected' : '' }}>Latino</option>
            </select>
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Profesión</span>
            </div>
            <select name="profesion" class="tipo form-control">
                <option {{ old('profesion') == 'Construcción' ? 'selected' : '' }}>Construcción</option>
                <option {{ old('profesion') == 'Minería' ? 'selected' : '' }}>Minería</option>
                <option {{ old('profesion') == 'Pintor' ? 'selected' : '' }}>Pintor</option>
                <option {{ old('profesion') == 'Peluquero' ? 'selected' : '' }}>Peluquero</option>
                <option {{ old('profesion') == 'Industria textil' ? 'selected' : '' }}>Industria textil</option>
                <option {{ old('profesion') == 'Mecánico' ? 'selected' : '' }}>Mecánico</option>
                <option {{ old('profesion') == 'Limpieza' ? 'selected' : '' }}>Limpieza</option>
                <option {{ old('profesion') == 'Cerámicas' ? 'selected' : '' }}>Cerámicas</option>
                <option {{ old('profesion') == 'Desconocido' ? 'selected' : '' }}>Desconocido</option>
                <option {{ old('profesion') == 'Otro' ? 'selected' : '' }}>Otro</option>
            </select>
        </div>
        <div class="oculto ml-2 my-4 input-group">
          <div class="input-group-prepend">
              <span class="input-group-text">Especificar <br>profesión</span>
          </div>
          <input name="profesion_especificar" value="{{ old('profesion_especificar') }}" class="form-control" autocomplete="off">
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Fumador</span>
            </div>
            <select name="fumador" class="tipo form-control">
                <option {{ old('fumador') == 'Fumador' ? 'selected' : '' }}>Fumador</option>
                <option {{ old('fumador') == 'Exfumador' ? 'selected' : '' }}>Exfumador</option>
                <option {{ old('fumador') == 'Nunca fumador' ? 'selected' : '' }}>Nunca fumador</option>
                <option {{ old('fumador') == 'Desconocido' ? 'selected' : '' }}>Desconocido</option>
            </select>
        </div>
        <div class="oculto ml-2 my-4 input-group">
          <div class="input-group-prepend">
              <span class="input-group-text">Número de<br> cigarros al día</span>
          </div>
          <input value="{{ old('especificar_fumador') }}" name="especificar_fumador" class="form-control" autocomplete="off">
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Bebedor</span>
            </div>
            <select name="bebedor" value="{{ old('bebedor') }}" class="form-control">
                <option {{ old('bebedor') == 'Bebedor' ? 'selected' : '' }}>Bebedor</option>
                <option {{ old('bebedor') == 'Exbebedor' ? 'selected' : '' }}>Exbebedor</option>
                <option {{ old('bebedor') == 'Nunca bebedor' ? 'selected' : '' }}>Nunca bebedor</option>
                <option {{ old('bebedor') == 'Desconocido' ? 'selected' : '' }}>Desconocido</option>
            </select>
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Carcinógenos</span>
            </div>
            <select name="carcinogenos" value="{{ old('carcinogenos') }}" class="tipo form-control">
                <option {{ old('carcinogenos') == 'Asbesto' ? 'selected' : '' }}>Asbesto</option>
                <option {{ old('carcinogenos') == 'Otro' ? 'selected' : '' }}>Otro</option>
                <option {{ old('carcinogenos') == 'Desconocido' ? 'selected' : '' }}>Desconocido</option>
            </select>
        </div>
        <div class="oculto ml-2 my-4 input-group">
          <div class="input-group-prepend">
              <span class="input-group-text">Especificar <br>carcinógeno</span>
          </div>
          <input value="{{ old('especificar_carcinogeno') }}" name="especificar_carcinogeno" class="form-control" autocomplete="off">
        </div>
        <div class="d-flex justify-content-center">
            <button id="boton_crearpaciente" type="submit" class="btn btn-primary">Confirmar</button>
        </div>
    </div>
</form>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection
