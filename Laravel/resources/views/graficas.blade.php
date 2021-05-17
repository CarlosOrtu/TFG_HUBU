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
        <select name="opcion" class="tipo form-control">
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
    <button type="submit" class="btn btn-primary">Mostrar grafica</button>
  </div>
</form>
@endsection