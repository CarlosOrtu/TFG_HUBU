@extends('layouts.app')
 
@section('content')
<div class="d-flex justify-content-between mb-4">
    @env('production')
    <h6 class="align-self-end text-white">Paciente: {{ $nombre }}</h6>
    @endenv
    @env('local')
    <h6 class="align-self-end text-white">Paciente: {{ $paciente->nombre }}</h6>
    @endenv
    <h1 class="align-self-center text-white panel-title">Nuevo seguimiento</h1>
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
@error('fecha_fallecimiento')
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@error('motivo_especificar')
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
<form action="{{ route('crearseguimiento', ['id' => $paciente->id_paciente]) }}" method="post">
    @CSRF
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Fecha <br>seguimiento</span>
      </div>
      <input name="fecha" type="date" class="form-control" autocomplete="off">
    </div>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Estado actual <br> del paciente</span>
      </div>
      <select name="estado" class="tipoTres form-control">
        <option>Vivo sin enfermedad</option>
        <option>Vivo con enfermedad</option>
        <option>Fallecido</option>
      </select>
    </div>
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Motivo del <br>fallecimiento</span>
      </div>
      <select name="motivo" class="form-control">
        <option>Enfermedad</option>
        <option>Otro</option>
      </select>    
    </div>
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Fecha del <br> fallecimiento</span>
      </div>
      <input name="fecha_fallecimiento" type="date" class="form-control" autocomplete="off">
    </div>
    <div class="d-flex justify-content-center mb-4">
        <button type="submit" class="btn btn-primary">Crear seguimiento</button>
    </div>
</form>
<script src="{{ asset('/js/nuevocampo.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/especificar_otro.js?v=0.1') }}" type="text/javascript"></script>
@endsection
