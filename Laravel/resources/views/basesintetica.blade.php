@extends('layouts.app')

@section('content')
<form action="{{ route('crearbasesintetica') }}" method="post">
@CSRF
  <div class="row">
    <div class="col-md-3">
      <label class="text-white">Crear base de datos sintética</label>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Número de<br> pacientes a <br>crear</span>
        </div>
        <input name="num_pacientes" min="0" step="1" type="number" class="form-control" autocomplete="off">
      </div>
    </div>
  </div>
  <div class="dropdown-divider"></div>
  <div class="row">
    <div class="col-md-3">
      <label class="text-white">Tamaño del tumor principal<br>Siguiendo una <a href="https://es.wikipedia.org/wiki/Distribuci%C3%B3n_normal">distribución normal</a></label>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Media</span>
        </div>
        <input name="media_tamano_tumor" min="0" step="0.1" type="number" class="form-control" autocomplete="off">
      </div>
    </div>
    <div class="col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Desviación</span>
        </div>
        <input name="desviacion_tamano_tumor" min="0" step="0.1" type="number" class="form-control" autocomplete="off">
      </div>
    </div>
  </div>
  <div class="dropdown-divider"></div>
  <div class="row">
    <div class="col-md-3">
      <label class="text-white">Dosis (Grays)<br>Siguiendo una <a href="https://es.wikipedia.org/wiki/Distribuci%C3%B3n_normal">distribución normal</a></label>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Media</span>
        </div>
        <input name="media_dosis" min="0" step="0.1" type="number" class="form-control" autocomplete="off">
      </div>
    </div>
    <div class="col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Desviación</span>
        </div>
        <input name="desviacion_dosis" min="0" step="0.1" type="number" class="form-control" autocomplete="off">
      </div>
    </div>
  </div>
  <div class="dropdown-divider"></div>
  <div class="row">
    <div class="col-md-3">
      <label class="text-white">Número de cigarros al día<br>Siguiendo una <a href="https://es.wikipedia.org/wiki/Distribuci%C3%B3n_de_Poisson">distribución de poisson</a></label>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Valor de λ</span>
        </div>
        <input name="lambda_cigarros" min="0" step="0.1" type="number" class="form-control" autocomplete="off">
      </div>
    </div>
  </div>
  <div class="dropdown-divider"></div>
  <div class="row">
    <div class="col-md-3">
      <label class="text-white">Número de ciclos (Quimioterapia)<br>Siguiendo una <a href="https://es.wikipedia.org/wiki/Distribuci%C3%B3n_de_Poisson">distribución de poisson</a></label>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Valor de λ</span>
        </div>
        <input name="lambda_ciclos" min="0" step="0.1" type="number" class="form-control" autocomplete="off">
      </div>
    </div>
  </div>
  <div class="dropdown-divider"></div>
  <div class="row">
    <div class="col-md-3">
      <label class="text-white">Número de veces que se repite cada tabla (sintomas, antecedentes, seguimientos, etc)<br>Siguiendo una <a href="https://es.wikipedia.org/wiki/Distribuci%C3%B3n_geom%C3%A9trica">distribución geométrica</a></label>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Valor de p</span>
        </div>
        <input name="p_tablas" min="0" step="0.1" type="number" class="form-control" autocomplete="off">
      </div>
    </div>
  </div>
  <div class="d-flex justify-content-center mb-4">
    <button type="submit" class="btn btn-primary">Crear base de datos</button>
  </div>
</form> 
@endsection