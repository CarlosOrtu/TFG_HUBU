@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h6 class="align-self-end text-white">Paciente: {{ $nombre }}</h6>
    <h1 class="align-self-center text-white panel-title">Metástasis</h1>
    <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@foreach ($paciente->Enfermedad->Metastasis as $Metastasis)
<form action="{{ route('metastasismodificar', ['id' => $paciente->id_paciente, 'num_metastasis' => $loop->index]) }}" method="post">
    @CSRF
    @method('put')
    <h4 class="text-white panel-title">Metástasis {{ $loop->iteration }}</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Localización</span>
      </div>
      <select name="localizacion" class="tipo form-control">
        <option {{ $Metastasis->localizacion == 'Pulmón contralatera' ? 'selected' : '' }}>Pulmón contralatera</option>
        <option {{ $Metastasis->localizacion == 'Implantes pleurales' ? 'selected' : '' }}>Implantes pleurales</option>
        <option {{ $Metastasis->localizacion == 'Derrame pleural' ? 'selected' : '' }}>Derrame pleural</option>
        <option {{ $Metastasis->localizacion == 'Hígado' ? 'selected' : '' }}>Hígado</option>
        <option {{ $Metastasis->localizacion == 'Hueso' ? 'selected' : '' }}>Hueso</option>
        <option {{ $Metastasis->localizacion == 'Suprarrenal' ? 'selected' : '' }}>Suprarrenal</option>
        <option {{ $Metastasis->localizacion == 'Renal' ? 'selected' : '' }}>Renal</option>
        <option {{ $Metastasis->localizacion == 'SNC' ? 'selected' : '' }}>SNC</option>
        <option {{ $Metastasis->localizacion == 'Derrame pericárdio' ? 'selected' : '' }}>Derrame pericárdio</option>
        <option {{ $Metastasis->localizacion == 'Carcinomatosis meníngea' ? 'selected' : '' }}>Carcinomatosis meníngea</option>
        <option {{ $Metastasis->localizacion == 'Linfangitis pulmonar carcinomatosa' ? 'selected' : '' }}>Linfangitis pulmonar carcinomatosa</option>
        <option {{ $Metastasis->localizacion == 'Adenopatías supradiafragmáticas extratorácicas' ? 'selected' : '' }}>Adenopatías supradiafragmáticas extratorácicas</option>
        <option {{ $Metastasis->localizacion == 'Adenopatías infradiafragmáticas' ? 'selected' : '' }}>Adenopatías infradiafragmáticas</option>
        <option {{ $Metastasis->localizacion == 'Páncreas' ? 'selected' : '' }}>Páncreas</option>
        <option {{ $Metastasis->localizacion == 'Peritoneo' ? 'selected' : '' }}>Peritoneo</option>
        <option {{ $Metastasis->localizacion == 'Cutánea' ? 'selected' : '' }}>Cutánea</option>
        <option {{ $Metastasis->localizacion == 'Muscular' ? 'selected' : '' }}>Muscular</option>
        <option {{ $Metastasis->localizacion == 'Tejidos blandos' ? 'selected' : '' }}>Tejidos blandos</option>
        <option {{ preg_match("/^Otro: /", $Metastasis->localizacion) ? 'selected' : '' }}>Otro</option>
      </select>
    </div>
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar <br>localización</span>
      </div>
      @if(preg_match("/^Otro: /", $Metastasis->localizacion))
      <input value="{{ substr($Metastasis->localizacion, 6) }}" name="localizacion_especificar" class="form-control" autocomplete="off">
      @else
      <input name="localizacion_especificar" class="form-control" autocomplete="off">
      @endif
    </div>
    <div class="d-flex justify-content-center">
      <button type="submit" class="btn btn-primary">Modificar</button>
</form>
      <form action="{{ route('metastasiseliminar', ['id' => $paciente->id_paciente, 'num_metastasis' => $loop->index]) }}" method="post">
        @CSRF
        @method('delete')
        <button class="ml-2 btn btn-warning">Eliminar</button>
      </form>
    </div>
<div class="my-4 dropdown-divider"></div>
@endforeach
<div class="mb-4 d-flex justify-content-strat">
    <button id="boton_nuevocampo" class="btn btn-info">Nueva metástasis</button>
</div>
<form id="nuevocampo" class="oculto" action="{{ route('metastasiscrear', ['id' => $paciente->id_paciente]) }}" method="post">
    @CSRF
    <h4 class="text-white panel-title">Nueva metástasis</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Localización</span>
      </div>
      <select name="localizacion" class="tipo form-control">
        <option>Pulmón contralatera</option>
        <option>Implantes pleurales</option>
        <option>Derrame pleural</option>
        <option>Hígado</option>
        <option>Hueso</option>
        <option>Suprarrenal</option>
        <option>Renal</option>
        <option>SNC</option>
        <option>Derrame pericárdio</option>
        <option>Carcinomatosis meníngea</option>
        <option>Linfangitis pulmonar carcinomatosa</option>
        <option>Adenopatías supradiafragmáticas extratorácicas</option>
        <option>Adenopatías infradiafragmáticas</option>
        <option>Páncreas</option>
        <option>Peritoneo</option>
        <option>Cutánea</option>
        <option>Muscular</option>
        <option>Tejidos blandos</option>
        <option>Otro</option>
      </select>
    </div>
    <div class="oculto ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar <br>localización</span>
      </div>
      <input name="localizacion_especificar" class="form-control" autocomplete="off">
    </div>
    <div class="d-flex justify-content-center mb-4">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
<script src="{{ asset('/js/nuevocampo.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection
