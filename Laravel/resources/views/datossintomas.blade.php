@extends('layouts.app')
  
@section('content')
<div class="d-flex justify-content-between mb-4">
    @env('production')
    <h6 class="align-self-end text-white">Paciente: {{ $nombre }}</h6>
    @endenv
    @env('local')
    <h6 class="align-self-end text-white">Paciente: {{ $paciente->nombre }}</h6>
    @endenv
    <h1 class="align-self-center text-white panel-title">Datos síntomas</h1>
    <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@error('fecha_inicio')
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@if ($message = Session::get('SQLerror'))
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@if($paciente->Enfermedad->Sintomas->first() !== null)
<form action="{{ route('modificarfechasintomas', ['id' => $paciente->id_paciente]) }}" method="post">
  @CSRF
  @method('put')
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Fecha inicio <br> de sintomas</span>
    </div>
    <input value="{{ $paciente->Enfermedad->Sintomas->first()->fecha_inicio }}" name="fecha_inicio" type="date" class="form-control" autocomplete="off">
  </div>
  <div class="d-flex justify-content-center">
    <button type="submit" class="btn btn-primary">Modificar</button>
  </div>
</form>
<div class="my-4 dropdown-divider"></div>
@endif
@foreach ($paciente->Enfermedad->Sintomas as $sintoma)
<form action="{{ route('datossintomasmodificar', ['id' => $paciente->id_paciente, 'num_sintoma' => $loop->index]) }}" method="post">
    @CSRF
    @method('put')
    <h4 class="text-white panel-title">Síntoma {{ $loop->iteration }}</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo síntoma</span>
      </div>
      <select name="tipo" class="tipoDoble form-control">
        <option {{ $sintoma->tipo == 'Asintomático' ? 'selected' : '' }}>Asintomático</option>
        <option {{ $sintoma->tipo == 'Tos' ? 'selected' : '' }}>Tos</option>
        <option {{ $sintoma->tipo == 'Pérdida de peso' ? 'selected' : '' }}>Pérdida de peso</option>
        <option {{ $sintoma->tipo == 'Anorexia' ? 'selected' : '' }}>Anorexia</option>
        <option {{ $sintoma->tipo == 'Aumento de expectoración' ? 'selected' : '' }}>Aumento de expectoración</option>
        <option {{ $sintoma->tipo == 'Hemoptisis' ? 'selected' : '' }}>Hemoptisis</option>
        <option {{ $sintoma->tipo == 'Dolor torácico' ? 'selected' : '' }}>Dolor torácico</option>
        <option {{ preg_match("/^Localización: /", $sintoma->tipo) ? 'selected' : '' }}>Dolor otra localización</option>
        <option {{ $sintoma->tipo == 'Clínica neurológica' ? 'selected' : '' }}>Clínica neurológica</option>
        <option {{ $sintoma->tipo == 'Fractura patológica' ? 'selected' : '' }}>Fractura patológica</option>
        <option {{ preg_match("/^Otro: /", $sintoma->tipo) ? 'selected' : '' }}>Otro</option>
        <option {{ $sintoma->tipo == 'Desconocido' ? 'selected' : '' }}>Desconocido</option>
      </select>
    </div> 
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar <br>síntoma</span>
      </div>
      @if(preg_match("/^Otro: /", $sintoma->tipo))
      <input value="{{ substr($sintoma->tipo, 6) }}" name="tipo_especificar" class="form-control" autocomplete="off">
      @else
      <input name="tipo_especificar" class="form-control" autocomplete="off">
      @endif
    </div>
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar <br>localización del <br>dolor</span>
      </div>
      @if(preg_match("/^Localización: /", $sintoma->tipo))
      <input value="{{ substr($sintoma->tipo, 14) }}" name="tipo_especificar_localizacion" class="form-control" autocomplete="off">
      @else
      <input name="tipo_especificar_localizacion" class="form-control" autocomplete="off">
      @endif
    </div>
    <div class="d-flex justify-content-center">
      <button type="submit" class="btn btn-primary">Modificar</button>
</form>
      <form action="{{ route('datossintomaseliminar', ['id' => $paciente->id_paciente, 'num_sintoma' => $loop->index]) }}" method="post">
        @CSRF
        @method('delete')
        <button class="ml-2 btn btn-warning">Eliminar</button>
      </form>
    </div>
<div class="my-4 dropdown-divider"></div>
@endforeach
<div class="mb-4 d-flex justify-content-strat">
    <button id="boton_nuevocampo" class="btn btn-info">Nuevo sintoma</button>
</div>
<form id="nuevocampo" class="oculto" action="{{ route('datossintomascrear', ['id' => $paciente->id_paciente, 'num_sintoma' => 0]) }}" method="post">
    @CSRF
    <h4 class="text-white panel-title">Nuevo síntoma</h4>
    @if($paciente->Enfermedad->Sintomas->first() === null)
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Fecha inicio <br> de sintomas</span>
      </div>
      <input name="fecha_inicio" type="date" class="form-control" autocomplete="off">
    </div>
    @endif
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo síntoma</span>
      </div>
      <select name="tipo" class="tipoDoble form-control">
        <option>Asintomático</option>
        <option>Tos</option>
        <option>Pérdida de peso</option>
        <option>Anorexia</option>
        <option>Aumento de expectoración</option>
        <option>Hemoptisis</option>
        <option>Dolor torácico</option>
        <option>Dolor otra localización</option>
        <option>Clínica neurológica</option>
        <option>Fractura patológica</option>
        <option>Otro</option>
        <option>Desconocido</option>
      </select>
    </div>
    <div class="ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar <br>síntoma</span>
      </div>
      <input name="tipo_especificar" class="form-control" autocomplete="off">
    </div>
    <div class="ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar <br>localización del <br>dolor</span>
      </div>
      <input name="tipo_especificar_localizacion" class="form-control" autocomplete="off">
    </div>
    <div class="d-flex justify-content-center mb-4">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
<script src="{{ asset('/js/nuevocampo.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection