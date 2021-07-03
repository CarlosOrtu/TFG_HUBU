@extends('layouts.app')

@section('content')
@if(isset($opciones))
<h3 class="mb-4 text-white text-center panel-title">Filtrado por: {{ $opciones }}</h3>
<form action="{{ route('realizarfiltradokaplan', ['opciones' => $opciones] ) }}" method="post">
@else
<form action="{{ route('crearkaplan') }}" method="post">
@endif
@CSRF
  <div class="row">
    <div class="col-md-3">
      <label class="text-white">Datos paciente</label>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Seleccionar <br>división <br> de la gráfica <br>Kaplan Meier</span>
        </div>
        <select name="separacion" class="form-control max">
          <option value="sexo">Sexo</option>
          <option value="raza">Raza</option>
          <option value="profesion">Profesión</option>
          <option value="fumador">Fumador</option>
          <option value="bebedor">Bebedor</option>
          <option value="carcinogenos">Carcinógenos</option>
        </select>
      </div>
    </div>
  </div>
  <div class="d-flex justify-content-center mb-4">
    <button type="submit" class="btn btn-primary">Mostrar percentiles</button>
  </div>
</form> 
@endsection