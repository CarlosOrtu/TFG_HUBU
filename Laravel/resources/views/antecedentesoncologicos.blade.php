@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h6 class="align-self-end text-white">Paciente: {{ $nombre }}</h6>
    <h1 class="align-self-center text-white panel-title">Antecendentes oncológicos</h1>
    <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@foreach ($paciente->Antecedentes_oncologicos as $antecedente)
<form action="{{ route('antecedenteoncologicomodificar', ['id' => $paciente->id_paciente, 'num_antecendente_oncologico' => $loop->index]) }}" method="post">
    @CSRF
    @method('put')
    <h4 class="text-white panel-title">Antecedente oncológico {{ $loop->iteration }}</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
      </div>
      <select name="tipo" class="tipo form-control">
        <option {{ $antecedente->tipo == 'Pulmón' ? 'selected' : '' }}>Pulmón</option>
        <option {{ $antecedente->tipo == 'ORL' ? 'selected' : '' }}>ORL</option>
        <option {{ $antecedente->tipo == 'Vejiga' ? 'selected' : '' }}>Vejiga</option>
        <option {{ $antecedente->tipo == 'Renal' ? 'selected' : '' }}>Renal</option>
        <option {{ $antecedente->tipo == 'Páncreas' ? 'selected' : '' }}>Páncreas</option>
        <option {{ $antecedente->tipo == 'Esofagogástrico' ? 'selected' : '' }}>Esofagogástrico</option>
        <option {{ $antecedente->tipo == 'Próstata' ? 'selected' : '' }}>Próstata</option>
        <option {{ $antecedente->tipo == 'Hígado' ? 'selected' : '' }}>Hígado</option>
        <option {{ $antecedente->tipo == 'Ginecológico' ? 'selected' : '' }}>Ginecológico</option>
        <option {{ $antecedente->tipo == 'Linfático' ? 'selected' : '' }}>Linfático</option>
        <option {{ $antecedente->tipo == 'SNC' ? 'selected' : '' }}>SNC</option>
        <option {{ preg_match("/^Otro: /", $antecedente->tipo) ? 'selected' : '' }}>Otro</option>
      </select>
    </div>
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar tipo</span>
      </div>
      @if(preg_match("/^Otro: /", $antecedente->tipo))
      <input value="{{ substr($antecedente->tipo, 6) }}" name="tipo_especificar" class="form-control" autocomplete="off">
      @else
      <input name="tipo_especificar" class="form-control" autocomplete="off">
      @endif
    </div>
    <div class="d-flex justify-content-center">
      <button type="submit" class="btn btn-primary">Modificar</button>
</form>
      <form action="{{ route('antecedenteoncologicoeliminar', ['id' => $paciente->id_paciente, 'num_antecendente_oncologico' => $loop->index]) }}" method="post">
        @CSRF
        @method('delete')
        <button class="ml-2 btn btn-warning">Eliminar</button>
      </form>
    </div>
<div class="my-4 dropdown-divider"></div>
@endforeach
<div class="mb-4 d-flex justify-content-strat">
    <button id="boton_nuevocampo" class="btn btn-info">Nueva antecendente oncológico</button>
</div>
<form id="nuevocampo" class="oculto" action="{{ route('antecedenteoncologicocrear', ['id' => $paciente->id_paciente]) }}" method="post">
    @CSRF
    <h4 class="text-white panel-title">Nuevo antecendente oncológico</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
      </div>
      <select name="tipo" class="tipo form-control">
        <option>Pulmón</option>
        <option>ORL</option>
        <option>Vejiga</option>
        <option>Renal</option>
        <option>Páncreas</option>
        <option>Esofagogástrico</option>
        <option>Próstata</option>
        <option>Hígado</option>
        <option>Ginecológico</option>
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
