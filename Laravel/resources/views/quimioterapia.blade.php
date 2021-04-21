@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h6 class="align-self-end text-white">Paciente: {{ $paciente->nombre }}</h6>
    <h1 class="align-self-center text-white panel-title">Quimioterapia</h1>
    <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
</div>
<?php
    $i = 1;
?>
@foreach ($paciente->Tratamientos->where('tipo','Quimioterapia') as $tratamiento)
<form action="{{ route('quimioterapiamodificar', ['id' => $paciente->id_paciente, 'num_quimioterapia' => $i]) }}" method="post">
  @CSRF
  @method('put')
  <h4 class="text-white panel-title">Quimioterapia {{ $i }}</h4>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Intención</span>
    </div>
    <select name="intencion" class="form-control">
      <option {{ $tratamiento->subtipo == 'Neoadyuvancia' ? 'selected' : '' }}>Neoadyuvancia</option>
      <option {{ $tratamiento->subtipo == 'Adyuvancia' ? 'selected' : '' }}>Adyuvancia</option>
      <option {{ $tratamiento->subtipo == 'Enfermedad avanzada' ? 'selected' : '' }}>Enfermedad avanzada</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Ensayo clínico</span>
    </div>
    <select name="ensayo_clinico" class="form-control">
      <option>Si</option>
      <option>No</option>
    </select>
  </div>
  <div class="ml-2 my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Tipo ensayo <br>clínico</span>
    </div>
    <select name="ensayo_clinico" class="form-control">
      <option>Observacional</option>
      <option>Experimental</option>
    </select>
  </div>
  <div class="ml-2 my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Ensayo clínico <br>fase</span>
    </div>
    <select name="ensayo_clinico" class="form-control">
      <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Tratamiento por <br>acceso<br> expandido</span>
    </div>
    <select name="tratamiento_acceso" class="form-control">
      <option>Si</option>
      <option>No</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Tratamiento <br>fuera de<br> indicacion</span>
    </div>
    <select name="tratamiento_fuera" class="form-control">
      <option>Si</option>
      <option>No</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Esquema</span>
    </div>
    <select name="esquema" class="form-control">
      <option>Monoterapia</option>
      <option>Combinación</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Modo de <br>administración</span>
    </div>
    <select name="administracion" class="tipo form-control">
      <option>Oral</option>
      <option>Intravenoso</option>
      <option>Otro</option>
    </select>
  </div>
  <div class="oculto ml-2 my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Especificar <br> modo de <br>administracion</span>
    </div>
    <input name="especificar_administracion" type="text" class="form-control" autocomplete="off">
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Tipo de <br>fármaco</span>
    </div>
    <select name="farmaco" class="tipo form-control">
      <option>Quimioterapia</option>
      <option>Inmunoterapia</option>
      <option>Tratamiento dirigido</option>
      <option>Antiangiogénico</option>
      <option>Quimoterapia + Inmunoterapia</option>
      <option>Tratamiento dirigido</option>
      <option>Quimioterapia</option>
      <option>quimioterapia + Tratamiento dirigido</option>
      <option>Quimioterapia + Antiangiogénico</option>
      <option>Otro</option>
    </select>
  </div>
  <div class="oculto ml-2 my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Especificar <br>fármaco</span>
    </div>
    <input name="especificar_farmaco" type="text" class="form-control" autocomplete="off">
  </div>
  <div class="d-flex justify-content-center">
      <button type="submit" class="btn btn-primary">Modificar</button>
</form>
      <form action="{{ route('quimioterapiaeliminar', ['id' => $paciente->id_paciente, 'num_quimioterapia' => $i]) }}" method="post">
        @CSRF
        @method('delete')
        <button class="ml-2 btn btn-warning">Eliminar</button>
      </form>
    </div>
<?php
  $i = $i + 1;
?>
@endforeach 


<script src="{{ asset('/js/nuevocampo.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection
