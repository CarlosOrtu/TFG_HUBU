@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h6 class="align-self-end text-white">Paciente: {{ $paciente->nombre }}</h6>
    <h1 class="align-self-center text-white panel-title">Antecedentes médicos</h1>
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
@foreach ($paciente->Antecedentes_medicos as $antecedente)
<form action="{{ route('antecedentemedicomodificar', ['id' => $paciente->id_paciente, 'num_antecendente_medico' => $i]) }}" method="post">
    @CSRF
    @method('put')
    <h4 class="text-white panel-title">Antecedente médico {{ $i }}</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
      </div>
      <select name="tipo" class="tipo form-control">
        <option {{ $antecedente->tipo_antecedente == 'HTA' ? 'selected' : '' }}>HTA</option>
        <option {{ $antecedente->tipo_antecedente == 'DM' ? 'selected' : '' }}>DM</option>
        <option {{ $antecedente->tipo_antecedente == 'DL' ? 'selected' : '' }}>DL</option>
        <option {{ $antecedente->tipo_antecedente == 'EPOC' ? 'selected' : '' }}>EPOC</option>
        <option {{ $antecedente->tipo_antecedente == 'Asma' ? 'selected' : '' }}>Asma</option>
        <option {{ $antecedente->tipo_antecedente == 'IAM' ? 'selected' : '' }}>IAM</option>
        <option {{ $antecedente->tipo_antecedente == 'Ictus' ? 'selected' : '' }}>Ictus</option>
        <option {{ $antecedente->tipo_antecedente == 'Enfermedad autoinmune' ? 'selected' : '' }}>Enfermedad autoinmune</option>
        <option {{ $antecedente->tipo_antecedente == 'VIH' ? 'selected' : '' }}>VIH</option>
        <option {{ $antecedente->tipo_antecedente == 'Tuberculosis' ? 'selected' : '' }}>Tuberculosis</option>
        <option {{ preg_match("/^Otro: /", $antecedente->tipo_antecedente) ? 'selected' : '' }}>Otro</option>
      </select>
    </div>
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar tipo</span>
      </div>
      @if(preg_match("/^Otro: /", $antecedente->tipo_antecedente))
      <input value="{{ substr($antecedente->tipo_antecedente, 6) }}" name="tipo_especificar" class="form-control" autocomplete="off">
      @else
      <input name="tipo_especificar" class="form-control" autocomplete="off">
      @endif
    </div>
    <div class="d-flex justify-content-center">
      <button type="submit" class="btn btn-primary">Modificar</button>
</form>
      <form action="{{ route('antecedentemedicoeliminar', ['id' => $paciente->id_paciente, 'num_antecendente_medico' => $i]) }}" method="post">
        @CSRF
        @method('delete')
        <button class="ml-2 btn btn-warning">Eliminar</button>
      </form>
    </div>
<?php
  $i = $i + 1;
?>
<div class="my-4 dropdown-divider"></div>
@endforeach
<div class="mb-4 d-flex justify-content-strat">
    <button id="boton_nuevocampo" class="btn btn-info">Nueva antecendente médico</button>
</div>
<form id="nuevocampo" class="oculto" action="{{ route('antecedentemedicocrear', ['id' => $paciente->id_paciente]) }}" method="post">
    @CSRF
    <h4 class="text-white panel-title">Nuevo antecendente médico</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
      </div>
      <select name="tipo" class="tipo form-control">
        <option>HTA</option>
        <option>DM</option>
        <option>DL</option>
        <option>EPOC</option>
        <option>Asma</option>
        <option>IAM</option>
        <option>Ictus</option>
        <option>Enfermedad autoinmune</option>
        <option>VIH</option>
        <option>Tuberculosis</option>
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
