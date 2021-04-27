@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h6 class="align-self-end text-white">Paciente: {{ $paciente->nombre }}</h6>
    <h1 class="align-self-center text-white panel-title">Radioterapia</h1>
    <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@error('dosis')
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@error('localizacion_especificar')
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
@error('fecha_fin')
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@foreach ($paciente->Tratamientos->where('tipo','Radioterapia') as $tratamiento)
<form action="{{ route('radioterapiamodificar', ['id' => $paciente->id_paciente, 'num_radioterapia' => $loop->index]) }}" method="post">
  @CSRF
  @method('put')
  <h4 class="text-white panel-title">Radioterapia {{ $loop->iteration }}</h4>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Intención</span>
    </div>
    <select name="intencion" class="form-control">
      <option {{ $tratamiento->subtipo == 'Radical' ? 'selected' : '' }}>Radical</option>
      <option {{ $tratamiento->subtipo == 'Paliativa' ? 'selected' : '' }}>Paliativa</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Localización</span>
    </div>
    <select name="localizacion" class="tipo form-control">
      <option {{ $tratamiento->localizacion == 'Pulmonar' ? 'selected' : '' }}>Pulmonar</option>
      <option {{ $tratamiento->localizacion == 'Pulmonar + mediastino' ? 'selected' : '' }}>Pulmonar + mediastino</option>
      <option {{ $tratamiento->localizacion == 'Ósea' ? 'selected' : '' }}>Ósea</option>
      <option {{ $tratamiento->localizacion == 'Suprarrenal' ? 'selected' : '' }}>Suprarrenal</option>
      <option {{ $tratamiento->localizacion == 'SNC' ? 'selected' : '' }}>SNC</option>
      <option {{ $tratamiento->localizacion == 'Hígado' ? 'selected' : '' }}>Hígado</option>
      <option {{ $tratamiento->localizacion == 'Ganglionar' ? 'selected' : '' }}>Ganglionar</option>
      <option {{ preg_match("/^Otro: /", $tratamiento->localizacion) ? 'selected' : '' }}>Otro</option>
    </select>
  </div>
  <div class="oculto ml-2 my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Especificar <br>localización</span>
    </div>
    @if(preg_match("/^Otro: /", $tratamiento->localizacion))
    <input value="{{ substr($tratamiento->localizacion, 6) }}" name="localizacion_especificar" class="form-control" autocomplete="off">
    @else
    <input name="localizacion_especificar" class="form-control" autocomplete="off">
    @endif
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Dosis (greys) </span>
    </div>
    <input value="{{ $tratamiento->dosis }}" name="dosis" type="number" step="0.1" class="form-control" autocomplete="off">
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Fecha inicio</span>
    </div>
    <input value="{{ $tratamiento->fecha_inicio }}" name="fecha_inicio" type="date" class="form-control" autocomplete="off">
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Fecha fin</span>
    </div>
    <input value="{{ $tratamiento->fecha_fin }}" name="fecha_fin" type="date" class="form-control" autocomplete="off">
  </div>
    <div class="d-flex justify-content-center">
      <button type="submit" class="btn btn-primary">Modificar</button>
</form>
      <form action="{{ route('radioterapiaeliminar', ['id' => $paciente->id_paciente, 'num_radioterapia' => $loop->index]) }}" method="post">
        @CSRF
        @method('delete')
        <button class="ml-2 btn btn-warning">Eliminar</button>
      </form>
    </div>
<div class="my-4 dropdown-divider"></div>
@endforeach  
<div class="mb-4 d-flex justify-content-strat">
    <button id="boton_nuevocampo" class="btn btn-info">Nueva radioterapia</button>
</div>
<form id="nuevocampo" class="oculto" action="{{ route('radioterapiacrear', ['id' => $paciente->id_paciente]) }}" method="post">
  @CSRF
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Intención</span>
    </div>
    <select name="intencion" class="form-control">
      <option>Radical</option>
      <option>Paliativa</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Localización</span>
    </div>
    <select name="localizacion" class="tipo form-control">
      <option>Pulmonar</option>
      <option>Pulmonar + mediastino</option>
      <option>Ósea</option>
      <option>Suprarrenal</option>
      <option>SNC</option>
      <option>Hígado</option>
      <option>Ganglionar</option>
      <option>Otro</option>
    </select>
  </div>
  <div class="oculto ml-2 my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Especificar <br>localización</span>
    </div>
    <input name="localizacion_especificar" class="form-control" autocomplete="off">
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Dosis (greys) </span>
    </div>
    <input name="dosis" type="number" step="0.1" class="form-control" autocomplete="off">
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Fecha inicio</span>
    </div>
    <input name="fecha_inicio" type="date" class="form-control" autocomplete="off">
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Fecha fin</span>
    </div>
    <input name="fecha_fin" type="date" class="form-control" autocomplete="off">
  </div>
  <div class="d-flex justify-content-center mb-4">
    <button type="submit" class="btn btn-primary">Guardar</button>
  </div>
</form>
<script src="{{ asset('/js/nuevocampo.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection
