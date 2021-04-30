@extends('layouts.app')
 
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h6 class="align-self-end text-white">Paciente: {{ $paciente->nombre }}</h6>
    <h1 class="align-self-center text-white panel-title">Seguimiento {{ $posicion+1 }}</h1>
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
@if ($message = Session::get('SQLerror'))
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
<form action="{{ route('modificarseguimiento', ['id' => $paciente->id_paciente, 'num_seguimiento' => $posicion]) }}" method="post">
    @CSRF
    @method('put')
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Fecha <br>seguimiento</span>
      </div>
      <input value="{{ $seguimiento->fecha }}" name="fecha" type="date" class="form-control" autocomplete="off">
    </div>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Estado actual <br> del paciente</span>
      </div>
      <select name="estado" class="tipoNuevo form-control">
        <option {{ $seguimiento->estado == 'Vivo sin enfermedad' ? 'selected' : '' }}>Vivo sin enfermedad</option>
        <option {{ $seguimiento->estado == 'Vivo con enfermedad' ? 'selected' : '' }}>Vivo con enfermedad</option>
        <option {{ $seguimiento->estado == 'Fallecido' ? 'selected' : '' }}>Fallecido</option>
      </select>
    </div>
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Motivo del <br>fallecimiento</span>
      </div>
      <select name="motivo" class="tipo form-control">
        <option {{ $seguimiento->fallecido_motivo == 'Fallecido' ? 'selected' : '' }}>Enfermedad</option>
        <option {{ preg_match("/^Otro: /", $seguimiento->fallecido_motivo) ? 'selected' : '' }}>Otro</option>
      </select>    
    </div>
    <div class="oculto ml-4 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar <br>fallecimiento</span>
      </div>
      @if(preg_match("/^Otro: /", $seguimiento->fallecido_motivo))
      <input value="{{ substr($seguimiento->fallecido_motivo, 6) }}" name="motivo_especificar" class="form-control" autocomplete="off">
      @else
      <input name="motivo_especificar" class="form-control" autocomplete="off">
      @endif
    </div>
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Fecha del <br> fallecimiento</span>
      </div>
      <input value="{{ $seguimiento->fecha_fallecimiento }}" name="fecha_fallecimiento" type="date" class="form-control" autocomplete="off">
    </div>
    <div class="d-flex justify-content-center mb-4">
        <button type="submit" class="btn btn-primary">Modificar</button>
</form>
      <form action="{{ route('eliminarseguimiento', ['id' => $paciente->id_paciente, 'num_seguimiento' => $posicion]) }}" method="post">
        @CSRF
        @method('delete')
        <button class="ml-2 btn btn-warning">Eliminar</button>
      </form>
    </div>
<script src="{{ asset('/js/nuevocampo.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection
