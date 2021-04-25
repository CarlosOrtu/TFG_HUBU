@extends('layouts.app')

@section('content')
<form method="POST">
    @CSRF
    <div class="col-md-12 pl-0">
        <h1 class="text-white text-center panel-title">Añadir paciente</h1>
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
            <select name="fumador" class="form-control">
                <option {{ old('fumador') == 1 ? 'selected' : '' }} value="1">Si</option>
                <option {{ old('fumador') == 0 ? 'selected' : '' }} value="0">No</option>
                <option {{ old('fumador') == 'desconocido' ? 'selected' : '' }} value="desconocido">Desconocido</option>
            </select>
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Bebedor</span>
            </div>
            <select name="bebedor" value="{{ old('bebedor') }}" class="form-control">
                <option {{ old('bebedor') == 1 ? 'selected' : '' }} value="1">Si</option>
                <option {{ old('bebedor') == 0 ? 'selected' : '' }} value="0">No</option>
                <option {{ old('bebedor') == 'desconocido' ? 'selected' : '' }} value="desconocido">Desconocido</option>
            </select>
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Carcinógenos</span>
            </div>
            <select name="carcinogenos" value="{{ old('carcinogenos') }}" class="form-control">
                <option {{ old('carcinogenos') == 1 ? 'selected' : '' }} value="1">Si</option>
                <option {{ old('carcinogenos') == 0 ? 'selected' : '' }} value="0">No</option>
                <option {{ old('carcinogenos') == 'desconocido' ? 'selected' : '' }} value="desconocido">Desconocido</option>
            </select>
        </div>
        <div class="d-flex justify-content-center">
            <button id="boton_crearpaciente" type="submit" class="btn btn-primary">Confirmar</button>
        </div>
    </div>
</form>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection
