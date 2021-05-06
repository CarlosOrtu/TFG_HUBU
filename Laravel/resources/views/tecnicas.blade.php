@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-4">
    @env('production')
    <h6 class="align-self-end text-white">Paciente: {{ $nombre }}</h6>
    @endenv
    @env('local')
    <h6 class="align-self-end text-white">Paciente: {{ $paciente->nombre }}</h6>
    @endenv
    <h1 class="align-self-center text-white panel-title">Técnicas realizadas</h1>
    <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@foreach ($paciente->Enfermedad->Tecnicas_realizadas as $tecnica)
<form action="{{ route('tecnicasmodificar', ['id' => $paciente->id_paciente, 'num_tecnica' => $loop->index]) }}" method="post">
    @CSRF
    @method('put')
    <h4 class="text-white panel-title">Técnica {{ $loop->iteration }}</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
      </div>
      <select name="tipo" class="tipo form-control">
        <option {{ $tecnica->tipo == 'Broncoscopia' ? 'selected' : '' }}>Broncoscopia</option>
        <option {{ $tecnica->tipo == 'EBUS' ? 'selected' : '' }}>EBUS</option>
        <option {{ $tecnica->tipo == 'Mediastinoscopia' ? 'selected' : '' }}>Mediastinoscopia</option>
        <option {{ $tecnica->tipo == 'BAG pulmonar' ? 'selected' : '' }}>BAG pulmonar</option>
        <option {{ $tecnica->tipo == 'BAG extrapulmonar' ? 'selected' : '' }}>BAG extrapulmonar</option>
        <option {{ $tecnica->tipo == 'Cirugía diagnóstico-terapéutica' ? 'selected' : '' }}>Cirugía diagnóstico-terapéutica</option>
        <option {{ preg_match("/^Otro: /", $tecnica->tipo) ? 'selected' : '' }}>Otro</option>
      </select>
    </div>
    <div class="oculto ml-2 my-4 input-group">
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
      <form action="{{ route('tecnicaseliminar', ['id' => $paciente->id_paciente, 'num_tecnica' => $loop->index]) }}" method="post">
        @CSRF
        @method('delete')
        <button class="ml-2 btn btn-warning">Eliminar</button>
      </form>
    </div>
<div class="my-4 dropdown-divider"></div>
@endforeach
<div class="mb-4 d-flex justify-content-strat">
    <button id="boton_nuevocampo" class="btn btn-info">Nueva técnica</button>
</div>
<form id="nuevocampo" class="oculto" action="{{ route('tecnicascrear', ['id' => $paciente->id_paciente]) }}" method="post">
    @CSRF
    <h4 class="text-white panel-title">Nueva técnica</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
      </div>
      <select name="tipo" class="tipo form-control">
        <option>Broncoscopia</option>
        <option>EBUS</option>
        <option>Mediastinoscopia</option>
        <option>BAG pulmonar</option>
        <option>BAG extrapulmonar</option>
        <option>Cirugía diagnóstico-terapéutica</option>
        <option>Otro</option>
      </select>
    </div>
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar tipo</span>
      </div>
      <input name="tipo_especificar" class="form-control" autocomplete="off">
    </div>
    <div class="d-flex justify-content-center mb-4">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
<script src="{{ asset('/js/nuevocampo.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection
