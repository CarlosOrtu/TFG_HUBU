@extends('layouts.app')

@section('content')
<div class="row rounded text-center">
  <p class="d-inline text-center border navbar rounded navbar-expand-md navbar-light shadow-sm text-white w-100">Se pueden seleccionar hasta 3 divisiones para realizar las gráficas</p>
</div>
@if ($message = Session::get('errorVacio'))
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@if ($message = Session::get('errorMax'))
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@if ($message = Session::get('errorNoExisteCampo'))
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
<form action="{{ route('imprimirgrafica') }}" method="post">
@CSRF
  <div class="row">
    <div class="col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Tipo de<br>gráfica</span>
        </div>
        <select name="tipo_grafica" class="form-control">
          <option value="circular">Gráfico circular</option>
          <option value="barras">Gráfico de barras</option>
        </select>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <label class="text-white">Datos personales</label>
    </div>
  </div>
  <div class="row">
    <div class="d-flex align-items-center col-md-3 my-4">
      <button type="button" class="boton_nuevocampo btn btn-info"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" id="datosPersonales" class="tipoGrafica form-control max">
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
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Elige intervalo<br> de edad</span>
        </div>
        <input name="edadIntervalo[]" type="number" min="1" class="tipo form-control"></input>
      </div>
    </div>
    <div class="oculto align-items-center col-md-3 my-4">
      <button type="button" class="boton_nuevocampo btn btn-info"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" id="datosPersonales" class="tipoGrafica form-control max">
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
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Elige intervalo<br> de edad</span>
        </div>
        <input name="edadIntervalo[]" type="number" min="1" class="tipo form-control"></input>
      </div>
    </div>
    <div class="oculto align-items-center col-md-3">
      <button type="button" id="boton_nuevocampo2" class="btn btn-info"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" id="datosPersonales" class="tipoGrafica form-control max">
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
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Elige intervalo<br> de edad</span>
        </div>
        <input name="edadIntervalo[]" type="number" min="1" class="tipo form-control"></input>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-2">
      <label class="text-white">Datos enfermedad especificos</label>
    </div>
  </div>
  <div class="row">
    <div class="d-flex align-items-center col-md-3 my-4">
      <button type="button" class="btn btn-info btn2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control max simple">
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
    <div class="oculto align-items-center col-md-3">
      <button type="button" class="btn btn-info btn2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control max simple">
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
    <div class="oculto align-items-center col-md-3">
      <button type="button" class="btn btn-info btn2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control max simple">
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
  <div class="row">
    <div class="col-md-3">
      <label class="text-white">Datos enfermedad generales</label>
    </div>
  </div>
  <div class="row">
    <div class="d-flex align-items-center col-md-3 my-4">
      <button type="button" class="btn btn-info btn2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control max simple">
          <option>Ninguna</option>
          <option value="tipo_sintoma">Tipos de sintoma</option>
          <option value="num_sintoma">Número de sintomas</option>
          <option value="tipo_metastasis">Tipos de metástasis</option>
          <option value="num_metastasis">Número de metástasis</option>
          <option value="tipo_biomarcador">Tipos de biomarcador</option>
          <option value="num_biomarcador">Número de biomarcadores</option>
          <option value="subtipo_biomarcador">Subtipos de biomarcador</option>
          <option value="tipo_prueba">Tipos de pruebas realizadas</option>
          <option value="num_prueba">Número de pruebas realizadas</option>
          <option value="tipo_tecnica">Tipos de técnicas realizadas</option>
          <option value="num_tecnica">Número de técnicas realizadas</option>
          <option value="tipo_tumor">Tipos de otros tumores</option>
          <option value="num_tumor">Número de otros tumores</option>
        </select>
      </div>
    </div>
    <div class="oculto align-items-center col-md-3">
      <button type="button" class="btn btn-info btn2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control max simple">
          <option>Ninguna</option>
          <option value="tipo_sintoma">Tipos de sintoma</option>
          <option value="num_sintoma">Número de sintomas</option>
          <option value="tipo_metastasis">Tipos de metástasis</option>
          <option value="num_metastasis">Número de metástasis</option>
          <option value="tipo_biomarcador">Tipos de biomarcador</option>
          <option value="num_biomarcador">Número de biomarcadores</option>
          <option value="subtipo_biomarcador">Subtipos de biomarcador</option>
          <option value="tipo_prueba">Tipos de pruebas realizadas</option>
          <option value="num_prueba">Número de pruebas realizadas</option>
          <option value="tipo_tecnica">Tipos de técnicas realizadas</option>
          <option value="num_tecnica">Número de técnicas realizadas</option>
          <option value="tipo_tumor">Tipos de otros tumores</option>
          <option value="num_tumor">Número de otros tumores</option>
        </select>
      </div>
    </div>
    <div class="oculto align-items-center col-md-3">
      <button type="button" class="btn btn-info btn2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control max simple">
          <option>Ninguna</option>
          <option value="tipo_sintoma">Tipos de sintoma</option>
          <option value="num_sintoma">Número de sintomas</option>
          <option value="tipo_metastasis">Tipos de metástasis</option>
          <option value="num_metastasis">Número de metástasis</option>
          <option value="tipo_biomarcador">Tipos de biomarcador</option>
          <option value="num_biomarcador">Número de biomarcadores</option>
          <option value="subtipo_biomarcador">Subtipos de biomarcador</option>
          <option value="tipo_prueba">Tipos de pruebas realizadas</option>
          <option value="num_prueba">Número de pruebas realizadas</option>
          <option value="tipo_tecnica">Tipos de técnicas realizadas</option>
          <option value="num_tecnica">Número de técnicas realizadas</option>
          <option value="tipo_tumor">Tipos de otros tumores</option>
          <option value="num_tumor">Número de otros tumores</option>
        </select>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <label class="text-white">Antecedentes</label>
    </div>
  </div>
  <div class="row">
    <div class="d-flex align-items-center col-md-3 my-4">
      <button type="button" class="btn btn-info btn2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control max simple">
          <option>Ninguna</option>
          <option value="tipo_antecedente_medico">Tipos de antecedentes medicos</option>
          <option value="num_antecedente_medico">Número de antecedentes medicos</option>
          <option value="tipo_antecedente_oncologico">Tipos de antecedentes oncológicos</option>
          <option value="num_antecedente_oncologico">Número de antecedentes oncológicos</option>
          <option value="familiar_antecedente">Familiares con antecedentes</option>
          <option value="num_familiar_antecedente">Número de familiares con antecedentes</option>
          <option value="tipo_antecedente_familiar">Tipos de antecedentes familiares</option>
          <option value="num_antecedente_familiar">Número de antecedentes familiares</option>
        </select>
      </div>
    </div>
    <div class="oculto align-items-center col-md-3">
      <button type="button" class="btn btn-info btn2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control max simple">
          <option>Ninguna</option>
          <option value="tipo_antecedente_medico">Tipos de antecedentes medicos</option>
          <option value="num_antecedente_medico">Número de antecedentes medicos</option>
          <option value="tipo_antecedente_oncologico">Tipos de antecedentes oncológicos</option>
          <option value="num_antecedente_oncologico">Número de antecedentes oncológicos</option>
          <option value="familiar_antecedente">Familiares con antecedentes</option>
          <option value="num_familiar_antecedente">Número de familiares con antecedentes</option>
          <option value="tipo_antecedente_familiar">Tipos de antecedentes familiares</option>
          <option value="num_antecedente_familiar">Número de antecedentes familiares</option>
        </select>
      </div>
    </div>
    <div class="oculto align-items-center col-md-3">
      <button type="button" class="btn btn-info btn2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control max simple">
          <option>Ninguna</option>
          <option value="tipo_antecedente_medico">Tipos de antecedentes medicos</option>
          <option value="num_antecedente_medico">Número de antecedentes medicos</option>
          <option value="tipo_antecedente_oncologico">Tipos de antecedentes oncológicos</option>
          <option value="num_antecedente_oncologico">Número de antecedentes oncológicos</option>
          <option value="familiar_antecedente">Familiares con antecedentes</option>
          <option value="num_familiar_antecedente">Número de familiares con antecedentes</option>
          <option value="tipo_antecedente_familiar">Tipos de antecedentes familiares</option>
          <option value="num_antecedente_familiar">Número de antecedentes familiares</option>
        </select>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <label class="text-white">Tratamientos</label>
    </div>
  </div>
  <div class="row">
    <div class="d-flex align-items-center col-md-3 my-4">
      <button type="button" class="btn btn-info btn2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control max simple">
          <option>Ninguna</option>
          <option value="tipo_tratamiento">Tipo de tratamientos</option>
          <option value="num_tratamientos">Numero de tratamientos</option>
          <option value="num_quimioterapia">Numero de quimioterapias</option>
          <option value="num_radioterapia">Numero de radioterapias</option>
          <option value="num_cirugia">Numero de cirugías</option>
          <option value="intencion_quimioterapia">Intención (Quimioterapia)</option>
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
    <div class="oculto align-items-center col-md-3">
      <button type="button" class="btn btn-info btn2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control max simple">
          <option>Ninguna</option>
          <option value="tipo_tratamiento">Tipo de tratamientos</option>
          <option value="num_tratamientos">Numero de tratamientos</option>
          <option value="num_quimioterapia">Numero de quimioterapias</option>
          <option value="num_radioterapia">Numero de radioterapias</option>
          <option value="num_cirugia">Numero de cirugías</option>
          <option value="intencion_quimioterapia">Intención (Quimioterapia)</option>
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
    <div class="oculto align-items-center col-md-3">
      <button type="button" class="btn btn-info btn2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control max simple">
          <option>Ninguna</option>
          <option value="tipo_tratamiento">Tipo de tratamientos</option>
          <option value="num_tratamientos">Numero de tratamientos</option>
          <option value="num_quimioterapia">Numero de quimioterapias</option>
          <option value="num_radioterapia">Numero de radioterapias</option>
          <option value="num_cirugia">Numero de cirugías</option>
          <option value="intencion_quimioterapia">Intención (Quimioterapia)</option>
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
  <div class="row">
    <div class="col-md-3">
      <label class="text-white">Seguimientos y reevaluaciones</label>
    </div>
  </div>
  <div class="row">
    <div class="d-flex align-items-center col-md-3 my-4">
      <button type="button" class="btn btn-info btn2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control max simple">
          <option>Ninguna</option>
          <option value="estado">Estado de reevaluación</option>
          <option value="num_reevaluacion">Número de reevaluaciones</option>
          <option value="num_seguimiento">Número de seguimientos</option>
          <option value="progresion_localizacion">Localización de la progresión</option>
          <option value="tipo_tratamiento">Tipo de tratamiento realizado</option>
          <option value="estado_seguimiento">Estado del paciente</option>
          <option value="fallecido_motivo">Motivo fallecimiento</option>
        </select>
      </div>
    </div>
    <div class="oculto align-items-center col-md-3">
      <button type="button" class="btn btn-info btn2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control max simple">
          <option>Ninguna</option>
          <option value="estado">Estado de reevaluación</option>
          <option value="num_reevaluacion">Número de reevaluaciones</option>
          <option value="num_seguimiento">Número de seguimientos</option>
          <option value="progresion_localizacion">Localización de la progresión</option>
          <option value="tipo_tratamiento">Tipo de tratamiento realizado</option>
          <option value="estado_seguimiento">Estado del paciente</option>
          <option value="fallecido_motivo">Motivo fallecimiento</option>
        </select>
      </div>
    </div>
    <div class="oculto align-items-center col-md-3">
      <button type="button" class="btn btn-info btn2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 mt-1 bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>Nueva división</button>
    </div>
    <div class="oculto col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Gráfica <br>dividida por</span>
        </div>
        <select name="opciones[]" class="form-control max simple">
          <option>Ninguna</option>
          <option value="estado">Estado de reevaluación</option>
          <option value="num_reevaluacion">Número de reevaluaciones</option>
          <option value="num_seguimiento">Número de seguimientos</option>
          <option value="progresion_localizacion">Localización de la progresión</option>
          <option value="tipo_tratamiento">Tipo de tratamiento realizado</option>
          <option value="estado_seguimiento">Estado del paciente</option>
          <option value="fallecido_motivo">Motivo fallecimiento</option>
        </select>
      </div>
    </div>
  </div>
  <div class="d-flex justify-content-center mb-4">
    <button type="submit" class="btn btn-primary">Mostrar gráfica</button>
  </div>
</form>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    $(".boton_nuevocampo").click(function(event) {
      $(this).parent().next().css('display', 'flex');
      $(this).parent().next().next().next().css('display', 'flex');
      $(this).parent().hide();
      $(this).parent().removeClass('d-flex');
    });
    $(".btn.btn-info.btn2").click(function(event) {
      $(this).parent().next().css('display', 'flex');
      $(this).parent().next().next().css('display', 'flex');
      $(this).parent().hide();
      $(this).parent().removeClass('d-flex');
    });
    $("#boton_nuevocampo2").click(function(event) {
      $(this).parent().next().css('display', 'flex');
      $(this).parent().hide();
      $(this).parent().removeClass('d-flex');
    });
    $(".form-control.max.simple").each(function(event){
      if($(this).val() != 'Ninguna'){
          $(this).parent().parent().prev().hide();
          $(this).parent().parent().prev().removeClass('d-flex');
          $(this).css('display', 'flex');
          $(this).parent().parent().css('display', 'flex');
          $(this).parent().parent().next().css('display', 'flex');
        }
      });
    $(".tipoGrafica.form-control.max").each(function(event){
      if($(this).val() != 'Ninguna'){
          $(this).parent().parent().prev().hide();
          $(this).parent().parent().prev().removeClass('d-flex');
          $(this).css('display', 'flex');
          $(this).parent().parent().css('display', 'flex');
          $(this).parent().parent().next().next().css('display', 'flex');
        }
      });
    $(".form-control.max").each(function(event) {
      var numDifNinguno = 0;
      $(".form-control.max").each(function(event){
        if($(this).val() != 'Ninguna')
          numDifNinguno += 1;
      });
      if(numDifNinguno == 3){
        $(".form-control.max").each(function(event){
          if($(this).val() == 'Ninguna'){
            $(".btn.btn-info").hide();
          }
        });
      }else{
        $(".form-control.max").each(function(event){
          if($(this).val() == 'Ninguna'){
            $(".btn.btn-info").css('display', 'flex');
          }
        });
      }
    });
    $(".form-control.max").change(function(event) {
      var numDifNinguno = 0;
      $(".form-control.max").each(function(event){
        if($(this).val() != 'Ninguna')
          numDifNinguno += 1;
      });
      if(numDifNinguno == 3){
        $(".form-control.max").each(function(event){
          if($(this).val() == 'Ninguna'){
            $(".btn.btn-info").hide();
          }
        });
      }else{
        $(".form-control.max").each(function(event){
          if($(this).val() == 'Ninguna'){
            $(".btn.btn-info").css('display', 'flex');
          }
        });
      }
    });
});
</script>
@endsection