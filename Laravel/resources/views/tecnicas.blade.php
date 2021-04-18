@extends('layouts.app')

@section('content')
<h1 class="text-white text-center panel-title">Técnicas</h1>
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
<?php
    $i = 0;
?>
@foreach ($paciente->Enfermedad->Tecnicas_realizadas as $tecnica)
<form action="{{ route('tecnicasmodificar', ['id' => $paciente->id_paciente, 'num_tecnica' => $i]) }}" method="post">
    @CSRF
    @method('put')
    <h4 class="text-white panel-title">Técnica {{ $i }}</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
      </div>
      <select name="tipo" id="tipo{{$i}}" class="tipo form-control">
        <option {{ $tecnica->tipo == 'Broncoscopia' ? 'selected' : '' }}>Broncoscopia</option>
        <option {{ $tecnica->tipo == 'EBUS' ? 'selected' : '' }}>EBUS</option>
        <option {{ $tecnica->tipo == 'Mediastinoscopia' ? 'selected' : '' }}>Mediastinoscopia</option>
        <option {{ $tecnica->tipo == 'BAG pulmonar' ? 'selected' : '' }}>BAG pulmonar</option>
        <option {{ $tecnica->tipo == 'BAG extrapulmonar' ? 'selected' : '' }}>BAG extrapulmonar</option>
        <option {{ $tecnica->tipo == 'Cirugía diagnóstico-terapéutica' ? 'selected' : '' }}>Cirugía diagnóstico-terapéutica</option>
        <option {{ preg_match("/^Otro: /", $tecnica->tipo) ? 'selected' : '' }}>Otro</option>
      </select>
    </div>
    <div id="especificar{{$i}}" class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar tipo</span>
      </div>
      @if(preg_match("/^Otro: /", $tecnica->tipo))
      <input value="{{ substr($tecnica->tipo, 6) }}" name="tipo_especificar" class="form-control" autocomplete="off">
      @else
      <input name="tipo_especificar" class="form-control" autocomplete="off">
      @endif
    </div>
    <div class="d-flex justify-content-center">
      <button type="submit" class="btn btn-primary">Modificar</button>
</form>
      <form action="{{ route('tecnicaseliminar', ['id' => $paciente->id_paciente, 'num_tecnica' => $i]) }}" method="post">
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
    <button id="boton_nuevocampo" class="btn btn-primary">Nueva tecnica</button>
</div>
<form id="nuevocampo" class="oculto" action="{{ route('tecnicascrear', ['id' => $paciente->id_paciente]) }}" method="post">
    @CSRF
    <h4 class="text-white panel-title">Nueva tecnica</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
      </div>
      <select name="tipo" class="tipo2 form-control">
        <option>Broncoscopia</option>
        <option>EBUS</option>
        <option>Mediastinoscopia</option>
        <option>BAG pulmonar</option>
        <option>BAG extrapulmonar</option>
        <option>Cirugía diagnóstico-terapéutica</option>
        <option>Otro</option>
      </select>
    </div>
    <div id="especificar_nueva" class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar tipo</span>
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