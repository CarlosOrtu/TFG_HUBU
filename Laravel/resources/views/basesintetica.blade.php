@extends('layouts.app')

@section('content')
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
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
        <input name="num_pacientes" value="{{ old('num_pacientes') }}" min="0" step="1" type="number" class="form-control @error('num_pacientes') is-invalid @enderror" autocomplete="off">
        @error('num_pacientes')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
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
        <input name="media_tamano_tumor" value="{{ old('media_tamano_tumor') }}" min="0" step="0.1" type="number" class="form-control @error('media_tamano_tumor') is-invalid @enderror" autocomplete="off">
        @error('media_tamano_tumor')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="boton_ayuda hover_ayuda bi bi-info-circle" viewBox="0 0 16 16">
      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
      <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
    </svg>
    <div class="col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Desviación</span>
        </div>
        <input name="desviacion_tamano_tumor" value="{{ old('desviacion_tamano_tumor') }}" min="0" step="0.1" type="number" class="form-control @error('desviacion_tamano_tumor') is-invalid @enderror" autocomplete="off">
        @error('desviacion_tamano_tumor')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="boton_ayuda hover_ayuda2 bi bi-info-circle" viewBox="0 0 16 16">
      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
      <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
    </svg>
    <div style="left:27%" class="ayuda position-absolute mt-4 rounded text-center">
      <p class="text-center border navbar rounded navbar-expand-md navbar-light shadow-sm text-white w-100">La media acepta tanto números enteros como números decimales separados por punto o por coma</p>
    </div>
    <div style="left:53%" class="ayuda position-absolute mt-4 rounded text-center">
      <p class="text-center border navbar rounded navbar-expand-md navbar-light shadow-sm text-white w-100">La desviación acepta tanto números enteros como números decimales separados por punto o por coma</p>
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
        <input name="media_dosis" value="{{ old('media_dosis') }}" min="0" step="0.1" type="number" class="form-control @error('media_dosis') is-invalid @enderror" autocomplete="off">
        @error('media_dosis')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="boton_ayuda hover_ayuda bi bi-info-circle" viewBox="0 0 16 16">
      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
      <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
    </svg>
    <div class="col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Desviación</span>
        </div>
        <input name="desviacion_dosis" value="{{ old('desviacion_dosis') }}" min="0" step="0.1" type="number" class="form-control @error('desviacion_dosis') is-invalid @enderror" autocomplete="off">
        @error('desviacion_dosis')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="boton_ayuda hover_ayuda2 bi bi-info-circle" viewBox="0 0 16 16">
      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
      <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
    </svg>
    <div style="left:27%" class="ayuda position-absolute mt-4 rounded text-center">
      <p class="text-center border navbar rounded navbar-expand-md navbar-light shadow-sm text-white w-100">La media acepta tanto números enteros como números decimales separados por punto o por coma</p>
    </div>
    <div style="left:53%" class="ayuda position-absolute mt-4 rounded text-center">
      <p class="text-center border navbar rounded navbar-expand-md navbar-light shadow-sm text-white w-100">La desviación acepta tanto números enteros como números decimales separados por punto o por coma</p>
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
        <input name="lambda_cigarros" value="{{ old('lambda_cigarros') }}" min="0" step="0.1" type="number" class="form-control @error('lambda_cigarros') is-invalid @enderror" autocomplete="off">
        @error('lambda_cigarros')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="boton_ayuda hover_ayuda3 bi bi-info-circle" viewBox="0 0 16 16">
    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
    </svg>
    <div style="left:27%" class="ayuda position-absolute mt-4 rounded text-center">
      <p class="text-center border navbar rounded navbar-expand-md navbar-light shadow-sm text-white w-100">Lambda acepta tanto números enteros como números decimales separados por punto o por coma</p>
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
        <input name="lambda_ciclos" value="{{ old('lambda_ciclos') }}" min="0" step="0.1" type="number" class="form-control @error('lambda_ciclos') is-invalid @enderror" autocomplete="off">
        @error('lambda_ciclos')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="boton_ayuda hover_ayuda3 bi bi-info-circle" viewBox="0 0 16 16">
    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
    </svg>
    <div style="left:27%" class="ayuda position-absolute mt-4 rounded text-center">
      <p class="text-center border navbar rounded navbar-expand-md navbar-light shadow-sm text-white w-100">Lambda acepta tanto números enteros como números decimales separados por punto o por coma</p>
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
        <input name="p_tablas" value="{{ old('p_tablas') }}" min="0" step="0.1" type="number" class="form-control @error('p_tablas') is-invalid @enderror" autocomplete="off">
        @error('p_tablas')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="boton_ayuda hover_ayuda3 bi bi-info-circle" viewBox="0 0 16 16">
    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
    </svg>
    <div style="left:27%" class="ayuda position-absolute mt-4 rounded text-center">
      <p class="text-center border navbar rounded navbar-expand-md navbar-light shadow-sm text-white w-100">p acepta valores decimales entre el 0 y 1 (1 incluido) separados por punto o por coma</p>
    </div>
  </div>
  <div class="d-flex justify-content-center mb-4">
    <button type="submit" class="btn btn-primary">Crear base de datos</button>
  </div>
</form> 
<script type="text/javascript">
  $(document).ready(function(){
    $('.ayuda').css( "display", "none" );

    $('.hover_ayuda').hover(function() {
      $(this).next().next().next().show();
    }, function(){
      $(this).next().next().next().hide();
    });

    $('.hover_ayuda2').hover(function() {
      $(this).next().next().show();
    }, function(){
      $(this).next().next().hide();
    });

    $('.hover_ayuda3').hover(function() {
      $(this).next().show();
    }, function(){
      $(this).next().hide();
    });

  });
</script>
@endsection