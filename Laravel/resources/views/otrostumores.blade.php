@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-4">
    @env('production')
    <h6 class="align-self-end text-white">Paciente: {{ $nombre }}</h6>
    @endenv
    @env('local')
    <h6 class="align-self-end text-white">Paciente: {{ $paciente->nombre }}</h6>
    @endenv
    <h1 class="align-self-center text-white panel-title">Otros tumores</h1>
    <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@foreach ($paciente->Enfermedades->Otros_tumores as $tumor)
<form action="{{ route('otrostumoresmodificar', ['id' => $paciente->id_paciente, 'num_otrostumores' => $loop->index]) }}" method="post">
    @CSRF
    @method('put')
    <h4 class="text-white panel-title">Tumor {{ $loop->iteration }}</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
      </div>
      <select name="tipo" class="tipo form-control">
        <option {{ $tumor->tipo == 'Pulmón' ? 'selected' : '' }}>Pulmón</option>
        <option {{ $tumor->tipo == 'ORL' ? 'selected' : '' }}>ORL</option>
        <option {{ $tumor->tipo == 'Vejiga' ? 'selected' : '' }}>Vejiga</option>
        <option {{ $tumor->tipo == 'Renal' ? 'selected' : '' }}>Renal</option>
        <option {{ $tumor->tipo == 'Colon' ? 'selected' : '' }}>Colon</option>
        <option {{ $tumor->tipo == 'Mama' ? 'selected' : '' }}>Mama</option>
        <option {{ $tumor->tipo == 'Páncreas' ? 'selected' : '' }}>Páncreas</option>
        <option {{ $tumor->tipo == 'Esofagogástrico' ? 'selected' : '' }}>Esofagogástrico</option>
        <option {{ $tumor->tipo == 'Próstata' ? 'selected' : '' }}>Próstata</option>
        <option {{ $tumor->tipo == 'Ginecológico' ? 'selected' : '' }}>Ginecológico</option>
        <option {{ $tumor->tipo == 'Hígado' ? 'selected' : '' }}>Hígado</option>
        <option {{ $tumor->tipo == 'Linfático' ? 'selected' : '' }}>Linfático</option>
        <option {{ $tumor->tipo == 'SNC' ? 'selected' : '' }}>SNC</option>
        <option {{ preg_match("/^Otro: /", $tumor->tipo) ? 'selected' : '' }}>Otro</option>
      </select>
    </div>
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar tipo</span>
      </div>
      @if(preg_match("/^Otro: /", $tumor->tipo))
      <input value="{{ substr($tumor->tipo, 6) }}" name="tipo_especificar" class="form-control" autocomplete="off">
      @else
      <input name="tipo_especificar" class="form-control" autocomplete="off">
      @endif
    </div>
    <div class="d-flex justify-content-center">
      <button type="submit" class="btn btn-primary">Modificar</button>
</form>
      <form action="{{ route('otrostumoreseliminar', ['id' => $paciente->id_paciente, 'num_otrostumores' => $loop->index]) }}" method="post">
        @CSRF
        @method('delete')
        <button class="ml-2 btn btn-warning">Eliminar</button>
      </form>
    </div>
<div class="my-4 dropdown-divider"></div>
@endforeach
<div class="mb-4 d-flex justify-content-strat">
    <button id="boton_nuevocampo" class="btn btn-info">Nuevo tumor</button>
</div>
<form id="nuevocampo" class="oculto" action="{{ route('otrostumorescrear', ['id' => $paciente->id_paciente]) }}" method="post">
    @CSRF
    <h4 class="text-white panel-title">Nuevo tumor</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
      </div>
      <select name="tipo" class="tipo form-control">
        <option>Pulmón</option>
        <option>ORL</option>
        <option>Vejiga</option>
        <option>Renal</option>
        <option>Colon</option>
        <option>Mama</option>
        <option>Páncreas</option>
        <option>Esofagogástrico</option>
        <option>Próstata</option>
        <option>Ginecológico</option>
        <option>Hígado</option>
        <option>Linfático</option>
        <option>SNC</option>
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
