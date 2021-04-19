@extends('layouts.app')
 
@section('content')
<h1 class="text-white text-center panel-title">Sintomas</h1>
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@error('tipo_especificar')
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@error('tipo_especificar_localizacion')
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@error('fecha_inicio')
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@if ($message = Session::get('SQLerror'))
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
<?php
    $i = 1;
?>
@foreach ($paciente->Enfermedad->Sintomas as $sintoma)
<form action="{{ route('datossintomasmodificar', ['id' => $paciente->id_paciente, 'num_sintoma' => $i]) }}" method="post">
    @CSRF
    @method('put')
    <h4 class="text-white panel-title">Sintoma {{ $i }}</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Fecha inicio</span>
      </div>
      <input value="{{ $sintoma->fecha_inicio }}" name="fecha_inicio" type="date" class="form-control" autocomplete="off">
    </div>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo sintoma</span>
      </div>
      <select name="tipo" id="tipo{{$i}}" class="tipo form-control">
        <option {{ $sintoma->tipo == 'Asintomático' ? 'selected' : '' }}>Asintomático</option>
        <option {{ $sintoma->tipo == 'Tos' ? 'selected' : '' }}>Tos</option>
        <option {{ $sintoma->tipo == 'Pérdida de peso' ? 'selected' : '' }}>Pérdida de peso</option>
        <option {{ $sintoma->tipo == 'Anorexia' ? 'selected' : '' }}>Anorexia</option>
        <option {{ $sintoma->tipo == 'Aumento de expectoración' ? 'selected' : '' }}>Aumento de expectoración</option>
        <option {{ $sintoma->tipo == 'Hemoptisis' ? 'selected' : '' }}>Hemoptisis</option>
        <option {{ $sintoma->tipo == 'Dolor torácico' ? 'selected' : '' }}>Dolor torácico</option>
        <option {{ preg_match("/^Localización: /", $sintoma->tipo) ? 'selected' : '' }}>Dolor otra localización</option>
        <option {{ $sintoma->tipo == 'Clínica neurológica' ? 'selected' : '' }}>Clínica neurológica</option>
        <option {{ $sintoma->tipo == 'Fractura patológica' ? 'selected' : '' }}>Fractura patológica</option>
        <option {{ preg_match("/^Otro: /", $sintoma->tipo) ? 'selected' : '' }}>Otro</option>
        <option {{ $sintoma->tipo == 'Desconocido' ? 'selected' : '' }}>Desconocido</option>
      </select>
    </div> 
    <div id="especificar_localizacion{{$i}}" class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar <br>localización del <br>dolor</span>
      </div>
      @if(preg_match("/^Localización: /", $sintoma->tipo))
      <input value="{{ substr($sintoma->tipo, 14) }}" name="tipo_especificar_localizacion" class="form-control" autocomplete="off">
      @else
      <input name="tipo_especificar_localizacion" class="form-control" autocomplete="off">
      @endif
    </div>
    <div id="especificar{{$i}}" class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar <br>sintoma</span>
      </div>
      @if(preg_match("/^Otro: /", $sintoma->tipo))
      <input value="{{ substr($sintoma->tipo, 6) }}" name="tipo_especificar" class="form-control" autocomplete="off">
      @else
      <input name="tipo_especificar" class="form-control" autocomplete="off">
      @endif
    </div>
    <div class="d-flex justify-content-center">
      <button type="submit" class="btn btn-primary">Modificar</button>
</form>
      <form action="{{ route('datossintomaseliminar', ['id' => $paciente->id_paciente, 'num_sintoma' => $i]) }}" method="post">
        @CSRF
        @method('delete')
        <button class="ml-2 btn btn-warning">Eliminar</button>
      </form>
    </div>
<?php
  $i = $i + 1;
?>
@endforeach
<div class="mb-4 d-flex justify-content-strat">
    <button id="boton_nuevocampo" class="btn btn-primary">Nuevo sintoma</button>
</div>
<form id="nuevocampo" class="oculto" action="{{ route('datossintomascrear', ['id' => $paciente->id_paciente, 'num_sintoma' => 0]) }}" method="post">
    @CSRF
    <h4 class="text-white panel-title">Nuevo sintoma</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Fecha inicio</span>
      </div>
      <input name="fecha_inicio" type="date" class="form-control" autocomplete="off">
    </div>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo sintoma</span>
      </div>
      <select name="tipo" class="tipo2 form-control">
        <option>Asintomático</option>
        <option>Tos</option>
        <option>Pérdida de peso</option>
        <option>Anorexia</option>
        <option>Aumento de expectoración</option>
        <option>Hemoptisis</option>
        <option>Dolor torácico</option>
        <option>Dolor otra localización</option>
        <option>Clínica neurológica</option>
        <option>Fractura patológica</option>
        <option>Otro</option>
        <option>Desconocido</option>
      </select>
    </div>
    <div id="especificar_localizacion_nueva" class="ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar <br>localización del <br>dolor</span>
      </div>
      <input name="tipo_especificar_localizacion" class="form-control" autocomplete="off">
    </div>
    <div id="especificar_nueva" class="ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar <br>sintoma</span>
      </div>
      <input name="tipo_especificar" class="form-control" autocomplete="off">
    </div>
    <div class="d-flex justify-content-center mb-4">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>

<script type="text/javascript">
  window.i = "<?php echo $i ?>"
</script>
<script src="{{ asset('/js/enfermedad.js') }}" type="text/javascript"></script>
@endsection