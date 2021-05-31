@extends('layouts.app')

@section('content')
<div class="row rounded text-center">
  <p class="d-inline text-center border navbar rounded navbar-expand-md navbar-light shadow-sm text-white w-100">Se pueden seleccionar hasta 3 divisiones para realizar las gráficas</p>
</div>
<form action="{{ route('imprimirgrafica') }}" method="post">
@CSRF
  <div class="row">
    <div class="col-md-3">
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
          <option value="raza">Edad</option>
          <option value="sexo">Número de cigarros</option>
          <option value="raza">Tamaño T</option>
          <option value="num_sintoma">Número de sintomas</option>
          <option value="num_metastasis">Número de metástasis</option>
          <option value="num_biomarcador">Número de biomarcadores</option>
          <option value="num_prueba">Número de pruebas realizadas</option>
          <option value="num_tecnica">Número de técnicas realizadas</option>
          <option value="num_tumor">Número de otros tumores</option>
          <option value="num_antecedente_medico">Número de antecedentes medicos</option>
          <option value="num_antecedente_oncologico">Número de antecedentes oncológicos</option>
          <option value="num_familiar_antecedente">Número de familiares con antecedentes</option>
          <option value="num_antecedente_familiar">Número de antecedentes familiares</option>
          <option value="num_tratamientos">Numero de tratamientos</option>
          <option value="num_quimioterapia">Numero de quimioterapias</option>
          <option value="num_radioterapia">Numero de radioterapias</option>
          <option value="num_cirugia">Numero de cirugías</option>
          <option value="numero_ciclos">Número de ciclos (Quimioterapia)</option>
          <option value="dosis">Dosis (Radioterapia)</option>
          <option value="num_reevaluacion">Número de reevaluaciones</option>
          <option value="num_seguimiento">Número de seguimientos</option>
        </select>
      </div>
    </div>
  </div>
</form>
@endsection