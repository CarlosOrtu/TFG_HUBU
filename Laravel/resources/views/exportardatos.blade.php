@extends('layouts.app')

@section('content')
<form action="{{ route('exportardatos') }}" method="post">
@CSRF
  <div class="row">
    <div class="col-md-3">
      <label class="text-white">Exportar datos</label>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Nombre del<br> fichero</span>
        </div>
        <input name="nombre_excel" class="form-control" autocomplete="off">
      </div>
    </div>
  </div>
  <div class="d-flex justify-content-center mb-4">
    <button type="submit" class="btn btn-primary">Exportar datos</button>
  </div>
</form> 
@endsection