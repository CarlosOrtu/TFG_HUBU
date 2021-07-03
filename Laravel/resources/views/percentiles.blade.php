@extends('layouts.app')

@section('content')
@if(isset($opciones))
<h3 class="mb-4 text-white text-center panel-title">Filtrado por: {{ $opciones }}</h3>
<form action="{{ route('realizarfiltradopercentiles', ['opciones' => $opciones] ) }}" method="post">
@else
<form action="{{ route('imprimirpercentiles') }}" method="post">
@endif
@CSRF
  @if ($message = Session::get('errorNoExisteCampo'))
  <div class="alert alert-danger alert-block">
      <button type="button" class="text-dark close" data-dismiss="alert">x</button>
      <strong class="text-center text-dark">{{ $message }}</strong>
  </div>
  @endif
  <div class="row">
    <div class="col-md-5">
      <label class="text-white">Datos personales</label>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Seleccionar dato<br> del que obtener<br> percentil</span>
        </div>
        <select name="datosPercentil" class="form-control max">
          <option value="edad">Edad</option>
          <option value="num_tabaco_dia">Número de cigarros</option>
          <option value="T_tamano">Tamaño T</option>
          <option value="Sintomas">Número de sintomas</option>
          <option value="Metastasis">Número de metástasis</option>
          <option value="Biomarcadores">Número de biomarcadores</option>
          <option value="Pruebas">Número de pruebas realizadas</option>
          <option value="Tecnicas_realizadas">Número de técnicas realizadas</option>
          <option value="Otros_tumores">Número de otros tumores</option>
          <option value="Antecedentes_medicos">Número de antecedentes medicos</option>
          <option value="Antecedentes_oncologicos">Número de antecedentes oncológicos</option>
          <option value="Antecedentes_familiares">Número de familiares con antecedentes</option>
          <option value="Enfermedades_familiar">Número de antecedentes familiares</option>
          <option value="Tratamientos">Numero de tratamientos</option>
          <option value="Quimioterapia">Numero de quimioterapias</option>
          <option value="Radioterapia">Numero de radioterapias</option>
          <option value="Cirugia">Numero de cirugías</option>
          <option value="numero_ciclos">Número de ciclos (Quimioterapia)</option>
          <option value="dosis">Dosis (Radioterapia)</option>
          <option value="Reevaluaciones">Número de reevaluaciones</option>
          <option value="Seguimientos">Número de seguimientos</option>
        </select>
      </div>
    </div>
  </div>
  <div class="d-flex justify-content-center mb-4">
    <button type="submit" class="btn btn-primary">Mostrar percentiles</button>
  </div>
</form> 
@endsection