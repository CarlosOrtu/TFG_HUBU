@extends('layouts.app')

@section('content')
<form action="{{ route('imprimirgrafica') }}" method="post">
@CSRF
  <div class="row">
    <div class="col-md-2">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="tipoGrafica form-control">
          <option>Ninguna</option>
          <option value="sexo">Sexo</option>
          <option value="raza">Raza</option>
          <option value="nacimiento">Edad</option>
          <option value="profesion">Profesión</option>
          <option value="fumador">Fumador</option>
          <option value="num_tabaco_dia">Numero de cigarros</option>
          <option value="bebedor">Bebedor</option>
          <option value="carcinogenos">Carcinogenos</option>
        </select>
      </div>
    </div>
    <div class="oculto col-md-2">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Elige intervalo<br> de edad</span>
        </div>
        <input name="edadIntervalo" type="number" min="1" class="tipo form-control"></input>
      </div>
    </div>
    <div class="col-md-2">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control">
          <option>Ninguna</option>
          <option value="ECOG">ECOG</option>
          <option value="T">T</option>
          <option value="T_tamano">Tamaño T</option>
          <option value="N">N</option>
          <option value="N_afectacion">Afectación de N</option>
          <option value="M">M</option>
          <option value="num_afec_metas">Afectación ganglionar local</option>
          <option value="tipo_muestra">Tipo de muestra</option>
          <option value="histologia_tipo">Histología tipo</option>
          <option value="histologia_subtipo">Histología subtipo</option>
          <option value="histologia_grado">Histología grado</option>
          <option value="tratamiento_dirigido">Tratamiento dirigido</option>
        </select>
      </div>
    </div>
  </div>
  <div class="d-flex justify-content-center mb-4">
    <button type="submit" class="btn btn-primary">Mostrar gráfica</button>
  </div>
</form>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection