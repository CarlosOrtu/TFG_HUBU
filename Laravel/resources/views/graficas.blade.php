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
          @foreach(Schema::getColumnListing('Pacientes') as $columna)
          @if($loop->index >= 3)
          <option>{{ $columna }}</option>
          @endif
          @endforeach
        </select>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Mostrar grafica</button>
  </div>
</form>
@endsection