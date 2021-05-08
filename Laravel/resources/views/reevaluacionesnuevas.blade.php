@extends('layouts.app')
 
@section('content')
<div class="d-flex justify-content-between mb-4">
    @env('production')
    <h6 class="align-self-end text-white">Paciente: {{ $nombre }}</h6>
    @endenv
    @env('local')
    <h6 class="align-self-end text-white">Paciente: {{ $paciente->nombre }}</h6>
    @endenv
    <h1 class="align-self-center text-white panel-title">Nueva reevaluación</h1>
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
@error('localizacion_especificar')
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
<form action="{{ route('crearreevaluacion', ['id' => $paciente->id_paciente]) }}" method="post">
    @CSRF
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Fecha <br>reevaluación</span>
      </div>
      <input name="fecha" type="date" class="form-control" autocomplete="off">
    </div>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Estado</span>
      </div>
      <select name="estado" class="tipoNuevo form-control">
        <option>Sin evidencia de enfermedad/respuesta completa</option>
        <option>Respuesta parcial</option>
        <option>Enfermedad estable</option>
        <option>Progresión</option>
        <option>Recaída</option>
      </select>
    </div>
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Localización</span>
      </div>
      <select name="localizacion" class="tipo form-control">
        <option>Pulmón contralateral</option>
        <option>Implantes pleurales</option>
        <option>Derrame pleural</option>
        <option>Hígado</option>
        <option>Hueso</option>
        <option>Suprarrenal</option>
        <option>Renal</option>
        <option>SNC</option>
        <option>Derrame pericárdico</option>
        <option>Carcinomatosis meníngea</option>
        <option>Linfangitis pulmonar carcinomatosa</option>
        <option>Adenopatías supradiafragmáticas extratorácicas</option>
        <option>Adenopatías infragmáticas</option>
        <option>Páncreas</option>
        <option>Peritoneo</option>
        <option>Cutánea</option>
        <option>Muscular</option>
        <option>Tejidos blandos</option>
        <option>Otro</option>
      </select>
    </div>
    <div class="oculto ml-4 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar <br>localización</span>
      </div>
      <input name="localizacion_especificar" class="form-control" autocomplete="off">
    </div>
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo de <br> tratamiento</span>
      </div>
      <select name="tipo_tratamiento" class="tipo form-control">
        <option>Tratamiento activo</option>
        <option>Cuidados paliativos</option>
      </select>
    </div>
    <div class="d-flex justify-content-center mb-4">
        <button type="submit" class="btn btn-primary">Crear reevaluación</button>
    </div>
</form>
<script src="{{ asset('/js/nuevocampo.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection
