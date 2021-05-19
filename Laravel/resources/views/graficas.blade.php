@extends('layouts.app')

@section('content')
<form action="{{ route('imprimirgrafica') }}" method="post">
@CSRF
  <div class="row">
    <div class="col-md-2">
      <label class="text-white">Datos personales</label>
    </div>
    <div class="col-md-2">
      <label class="text-white">Datos enfermedad especificos</label>
    </div>
    <div class="col-md-2">
      <label class="text-white">Datos enfermedad generales</label>
    </div>
    <div class="col-md-2">
      <label class="text-white">Antecedentes</label>
    </div>
    <div class="col-md-2">
      <label class="text-white">Tratamientos</label>
    </div>
    <div class="col-md-2">
      <label class="text-white">Seguimientos y reevaluaciones</label>
    </div>
  </div>
  <div class="row">
    <div class="col-md-2">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" id="datosPersonales" class="tipoGrafica form-control">
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
    <div class="col-md-2">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control">
          <option>Ninguna</option>
          <option value="tipo_sintoma">Tipos de sintoma</option>
          <option value="tipo_metastasis">Tipos de metástasis</option>
          <option value="tipo_biomarcador">Tipos de biomarcador</option>
          <option value="subtipo_biomarcador">Subtipos de biomarcador</option>
          <option value="tipo_prueba">Tipos de pruebas realizadas</option>
          <option value="tipo_tecnica">Tipos de técnicas realizadas</option>
          <option value="tipo_tumor">Tipos de otros tumores</option>
        </select>
      </div>
    </div>
    <div class="col-md-2">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control">
          <option>Ninguna</option>
          <option value="tipo_antecedente_medico">Tipos de antecedentes medicos</option>
          <option value="tipo_antecedente_oncologico">Tipos de antecedentes oncológicos</option>
          <option value="familiar_antecedente">Familiares con antecedentes</option>
          <option value="tipo_antecedente_familiar">Tipos de antecedentes familiares</option>
        </select>
      </div>
    </div>
    <div class="col-md-2">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control">
          <option>Ninguna</option>
          <option value="tipo_tratamiento">Tipo de tratamientos</option>
          <option value="intencion_quimioterapia">Intención (Quimioterapia)</option>
          <option value="ensayo_quimioterapia">Ensayo clínico (Quimioterapia)</option>
          <option value="ensayo">Tipo de ensayo clínico (Quimioterapia)</option>
          <option value="ensayo_fase">Fase de ensayo clínico (Quimioterapia)</option>
          <option value="tratamiento_acceso_expandido">Tratamiento por acceso expandido (Quimioterapia)</option>
          <option value="tratamiento_fuera_indicacion">Tratamiento fuera de indicación (Quimioterapia)</option>
          <option value="medicacion_extranjera">Medicación extranjera (Quimioterapia)</option>
          <option value="esquema">Esquema (Quimioterapia)</option>
          <option value="modo_administracion">Modo de administración (Quimioterapia)</option>
          <option value="tipo_farmaco">Tipo de fármaco (Quimioterapia)</option>
          <option value="numero_ciclos">Número de ciclos (Quimioterapia)</option>
          <option value="duracion_quimioterapia">Duración (Quimioterapia)</option>
          <option value="farmacos_quimioterapia">Farmacos usados (Quimioterapia)</option>
          <option value="tipo_radioterapia">Intención (Radioterapia)</option>
          <option value="localizacion">Localización (Radioterapia)</option>
          <option value="dosis">Dosis (Radioterapia)</option>
          <option value="duracion_radioterapia">Duración (Radioterapia)</option>
          <option value="tipo_cirugia">Tipo (Cirugía)</option>
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