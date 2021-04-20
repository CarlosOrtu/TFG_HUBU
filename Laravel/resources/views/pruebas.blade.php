@extends('layouts.app')
 
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h6 class="align-self-end text-white">Paciente: {{ $paciente->nombre }}</h6>
    <h1 class="align-self-center text-white panel-title">Pruebas realizadas</h1>
    <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@error('tipo_especificar')
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
<?php
    $i = 1;
?>
@foreach ($paciente->Enfermedad->Pruebas_realizadas as $prueba)
<form action="{{ route('pruebasmodificar', ['id' => $paciente->id_paciente, 'num_prueba' => $i]) }}" method="post">
    @CSRF
    @method('put')
    <h4 class="text-white panel-title">Prueba {{ $i }}</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
      </div>
      <select name="tipo" class="tipo form-control">
        <option {{ $prueba->tipo == 'Radiografía de tórax' ? 'selected' : '' }}>Radiografía de tórax</option>
        <option {{ $prueba->tipo == 'TAC' ? 'selected' : '' }}>TAC</option>
        <option {{ $prueba->tipo == 'TAP' ? 'selected' : '' }}>TAP</option>
        <option {{ $prueba->tipo == 'TAC SNC' ? 'selected' : '' }}>TAC SNC</option>
        <option {{ $prueba->tipo == 'PETTAC' ? 'selected' : '' }}>PETTAC</option>
        <option {{ $prueba->tipo == 'GGO' ? 'selected' : '' }}>GGO</option>
        <option {{ $prueba->tipo == 'RMN' ? 'selected' : '' }}>RMN</option>
        <option {{ $prueba->tipo == 'SNC' ? 'selected' : '' }}>SNC</option>
        <option {{ $prueba->tipo == 'RMN body' ? 'selected' : '' }}>RMN body</option>
        <option {{ preg_match("/^Otro: /", $prueba->tipo) ? 'selected' : '' }}>Otro</option>
      </select>
    </div>
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar tipo</span>
      </div>
      @if(preg_match("/^Otro: /", $prueba->tipo))
      <input value="{{ substr($prueba->tipo, 6) }}" name="tipo_especificar" class="form-control" autocomplete="off">
      @else
      <input name="tipo_especificar" class="form-control" autocomplete="off">
      @endif
    </div>
    <div class="d-flex justify-content-center">
      <button type="submit" class="btn btn-primary">Modificar</button>
</form>
      <form action="{{ route('pruebaseliminar', ['id' => $paciente->id_paciente, 'num_prueba' => $i]) }}" method="post">
        @CSRF
        @method('delete')
        <button class="ml-2 btn btn-warning">Eliminar</button>
      </form>
    </div>
<?php
  $i = $i + 1;
?>
@endforeach
<div class="mb-4 d-flex justify-content-strat">
    <button id="boton_nuevocampo" class="btn btn-primary">Nueva prueba</button>
</div>
<form id="nuevocampo" class="oculto" action="{{ route('pruebascrear', ['id' => $paciente->id_paciente]) }}" method="post">
    @CSRF
    <h4 class="text-white panel-title">Nueva prueba</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
      </div>
      <select name="tipo" class="tipo form-control">
        <option>Radiografía de tórax</option>
        <option>TAC</option>
        <option>TAP</option>
        <option>TAC SNC</option>
        <option>PETTAC</option>
        <option>GGO</option>
        <option>RMN</option>
        <option>SNC</option>
        <option>RMN body</option>
        <option>Otro</option>
      </select>
    </div>
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar tipo</span>
      </div>
      <input name="tipo_especificar" class="form-control" autocomplete="off">
    </div>
    <div class="d-flex justify-content-center mb-4">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
<script src="{{ asset('/js/nuevocampo.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection
