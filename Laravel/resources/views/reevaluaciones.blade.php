@extends('layouts.app')
 
@section('content')
<div class="d-flex justify-content-between mb-4">
    @env('production')
    <h6 class="align-self-end text-white">Paciente: {{ $nombre }}</h6>
    @endenv
    @env('local')
    <h6 class="align-self-end text-white">Paciente: {{ $paciente->nombre }}</h6>
    @endenv
    <h1 class="align-self-center text-white panel-title">Reevaluación {{ $posicion+1 }}</h1>
    <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@error('fecha')
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
<form action="{{ route('modificareevaluacion', ['id' => $paciente->id_paciente, 'num_reevaluacion' => $posicion]) }}" method="post">
  @CSRF
  @method('put')
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Fecha <br>reevaluación</span>
      </div>
      <input value="{{ $reevaluacion->fecha }}" name="fecha" type="date" class="form-control" autocomplete="off">
    </div>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Estado</span>
      </div>
      <select name="estado" class="tipoNuevo form-control">
        <option {{ $reevaluacion->estado == 'Sin evidencia de enfermedad/respuesta completa' ? 'selected' : '' }}>Sin evidencia de enfermedad/respuesta completa</option>
        <option {{ $reevaluacion->estado == 'Respuesta parcial' ? 'selected' : '' }}>Respuesta parcial</option>
        <option {{ $reevaluacion->estado == 'Enfermedad estable' ? 'selected' : '' }}>Enfermedad estable</option>
        <option {{ $reevaluacion->estado == 'Progresión' ? 'selected' : '' }}>Progresión</option>
        <option {{ $reevaluacion->estado == 'Recaida' ? 'selected' : '' }}>Recaida</option>
      </select>
    </div>
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Localización</span>
      </div>
      <select name="localizacion" class="tipo form-control">
        <option {{ $reevaluacion->progresion_localizacion == 'Pulmón contralateral' ? 'selected' : '' }}>Pulmón contralateral</option>
        <option {{ $reevaluacion->progresion_localizacion == 'Implantes pleurales' ? 'selected' : '' }}>Implantes pleurales</option>
        <option {{ $reevaluacion->progresion_localizacion == 'Derrame pleural' ? 'selected' : '' }}>Derrame pleural</option>
        <option {{ $reevaluacion->progresion_localizacion == 'Hígado' ? 'selected' : '' }}>Hígado</option>
        <option {{ $reevaluacion->progresion_localizacion == 'Hueso' ? 'selected' : '' }}>Hueso</option>
        <option {{ $reevaluacion->progresion_localizacion == 'Suprarrenal' ? 'selected' : '' }}>Suprarrenal</option>
        <option {{ $reevaluacion->progresion_localizacion == 'Renal' ? 'selected' : '' }}>Renal</option>
        <option {{ $reevaluacion->progresion_localizacion == 'SNC' ? 'selected' : '' }}>SNC</option>
        <option {{ $reevaluacion->progresion_localizacion == 'Derrame pericárdico' ? 'selected' : '' }}>Derrame pericárdico</option>
        <option {{ $reevaluacion->progresion_localizacion == 'Carcinomatosis meníngea' ? 'selected' : '' }}>Carcinomatosis meníngea</option>
        <option {{ $reevaluacion->progresion_localizacion == 'Linfangitis pulmonar carcinomatosa' ? 'selected' : '' }}>Linfangitis pulmonar carcinomatosa</option>
        <option {{ $reevaluacion->progresion_localizacion == 'Adenopatías supradiafragmáticas extratorácicas' ? 'selected' : '' }}>Adenopatías supradiafragmáticas extratorácicas</option>
        <option {{ $reevaluacion->progresion_localizacion == 'Adenopatías infragmáticas' ? 'selected' : '' }}>Adenopatías infragmáticas</option>
        <option {{ $reevaluacion->progresion_localizacion == 'Páncreas' ? 'selected' : '' }}>Páncreas</option>
        <option {{ $reevaluacion->progresion_localizacion == 'Peritoneo' ? 'selected' : '' }}>Peritoneo</option>
        <option {{ $reevaluacion->progresion_localizacion == 'Cutánea' ? 'selected' : '' }}>Cutánea</option>
        <option {{ $reevaluacion->progresion_localizacion == 'Muscular' ? 'selected' : '' }}>Muscular</option>
        <option {{ $reevaluacion->progresion_localizacion == 'Tejidos blandos' ? 'selected' : '' }}>Tejidos blandos</option>
        <option {{ preg_match("/^Otro: /", $reevaluacion->progresion_localizacion) ? 'selected' : '' }}>Otro</option>
      </select>
    </div>
    <div class="ml-4 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar <br>localización</span>
      </div>
      @if(preg_match("/^Otro: /", $reevaluacion->progresion_localizacion))
      <input value="{{ substr($reevaluacion->progresion_localizacion, 6) }}" name="localizacion_especificar" class="form-control" autocomplete="off">
      @else
      <input name="localizacion_especificar" class="form-control" autocomplete="off">
      @endif
    </div>
    <div class="ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo de <br> tratamiento</span>
      </div>
      <select name="tipo_tratamiento" class="tipo form-control">
        <option {{ $reevaluacion->tipo_tratamiento == 'Tratamiento activo' ? 'selected' : '' }}>Tratamiento activo</option>
        <option {{ $reevaluacion->tipo_tratamiento == 'Cuidados paliativos' ? 'selected' : '' }}>Cuidados paliativos</option>
      </select>
    </div>
    <div class="d-flex justify-content-center mb-4">
        <button type="submit" class="btn btn-primary">Modificar</button>
</form>
      <form action="{{ route('eliminarreevaluacion', ['id' => $paciente->id_paciente, 'num_reevaluacion' => $posicion]) }}" method="post">
        @CSRF
        @method('delete')
        <button class="ml-2 btn btn-warning">Eliminar</button>
      </form>
    </div>
<script src="{{ asset('/js/nuevocampo.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection