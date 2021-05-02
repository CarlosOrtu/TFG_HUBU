@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h6 class="align-self-end text-white">Paciente: {{ $nombre }}</h6>
    <h1 class="align-self-center text-white panel-title">Antecedentes familiares</h1>
    <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@foreach ($paciente->Antecedentes_familiares as $antecedente)
<form action="{{ route('antecedentefamiliarmodificar', ['id' => $paciente->id_paciente, 'num_antecendente_familiar' => $loop->index]) }}" method="post">
  @CSRF
  @method('put')
  <h4 class="text-white panel-title">Familiar {{ $loop->iteration  }}</h4>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Familiar</span>
    </div>
    <input name="familiar" class="form-control" value="{{$antecedente->familiar}}" autocomplete="off">
  </div>
    @foreach ($antecedente->Enfermedades_familiar as $enfermedad)
    <div>
    <div id="especificar" class="ml-2 my-4 input-group">
      <div class="input-group-prepend">
        <span class="input-group-text">Tipo de cancer</span>
      </div>
      <select name="enfermedades[]" class="tipo form-control">
        <option {{ $enfermedad->tipo == 'Pulmón' ? 'selected' : '' }}>Pulmón</option>
        <option {{ $enfermedad->tipo == 'ORL' ? 'selected' : '' }}>ORL</option>
        <option {{ $enfermedad->tipo == 'Vejiga' ? 'selected' : '' }}>Vejiga</option>
        <option {{ $enfermedad->tipo == 'Renal' ? 'selected' : '' }}>Renal</option>
        <option {{ $enfermedad->tipo == 'Páncreas' ? 'selected' : '' }}>Páncreas</option>
        <option {{ $enfermedad->tipo == 'Esofagogástrico' ? 'selected' : '' }}>Esofagogástrico</option>
        <option {{ $enfermedad->tipo == 'Próstata' ? 'selected' : '' }}>Próstata</option>
        <option {{ $enfermedad->tipo == 'Hígado' ? 'selected' : '' }}>Hígado</option>
        <option {{ $enfermedad->tipo == 'Ginecológico' ? 'selected' : '' }}>Ginecológico</option>
        <option {{ $enfermedad->tipo == 'Linfático' ? 'selected' : '' }}>Linfático</option>
        <option {{ $enfermedad->tipo == 'SNC' ? 'selected' : '' }}>SNC</option>
        <option {{ preg_match("/^Otro: /", $enfermedad->tipo) ? 'selected' : '' }}>Otro</option>
      </select>
    </div>
    <div class="oculto tipo-especificar ml-4 my-4 input-group">
      <div class="input-group-prepend">
        <span class="input-group-text">Especificar tipo</span>
      </div>
      @if(preg_match("/^Otro: /", $enfermedad->tipo))
      <input value="{{ substr($enfermedad->tipo, 6) }}" name="tipos_especificar[]" class="form-control" autocomplete="off">
      @else
      <input name="tipos_especificar[]" class="form-control" autocomplete="off">
      @endif
    </div>
    @endforeach
  <button type="button" class="btn-anadir btn btn-info mb-4">Añadir cancer</button>
    <div class="d-flex justify-content-center">
      <button type="submit" class="btn btn-primary">Modificar</button>
</form>
      <form action="{{ route('antecedentefamiliareliminar', ['id' => $paciente->id_paciente, 'num_antecendente_familiar' => $loop->index]) }}" method="post">
        @CSRF
        @method('delete')
        <button class="ml-2 btn btn-warning">Eliminar</button>
      </form>
    </div>
<div class="my-4 dropdown-divider"></div>
@endforeach
<div class="mb-4 d-flex justify-content-strat">
    <button id="boton_nuevocampo" type="button" class="btn btn-info">Nuevo familiar</button>
</div>
<form id="nuevocampo" class="oculto" action="{{ route('antecedentefamiliarcrear', ['id' => $paciente->id_paciente]) }}" method="post">
    @CSRF
    <h4 class="text-white panel-title">Familiar con antecedentes</h4>
    <div class="my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Familiar</span>
      </div>
      <input name="familiar" class="form-control" autocomplete="off">
    </div>
    <button type="button" class="btn-anadir btn btn-info">Añadir cancer</button>
    <div class="d-flex justify-content-center mb-4">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
<script type="text/javascript">
  $(document).ready(function(){
    //Definición de el html que se van a insertar
    var enfermedad = '<div class="ml-2 my-4 input-group"><div class="input-group-prepend"><span class="input-group-text">Tipo de cancer</span></div><select onChange="anadir(this)" name="enfermedades[]" class="tipo form-control"><option>Pulmón</option><option>ORL</option><option>Vejiga</option><option>Renal</option><option>Páncreas</option><option>Esofagogástrico</option><option>Próstata</option><option>Hígado</option><option>Ginecológico</option><option>Linfático</option><option>SNC</option><option>Otro</option></select></div>';

    //Función que añade un nuevo tipo de cancer dentro del familiar
    $(".btn-anadir").click(function(){
      $(this).before(enfermedad);
    });
  });
</script>
<script type="text/javascript">
  //Función que va a añadir el campo especificar tipo en el caso que el select sea Otro, en caso contrario lo oculta
  function anadir(element){
    var modificarEnfermedadHTML = '<div class="tipo-especificar ml-4 mt-4 input-group"><div class="input-group-prepend"><span class="input-group-text">Especificar tipo</span></div><input name="tipos_especificar[]" class="form-control" autocomplete="off"></div>'
    var valorSelect = $(element).val();
    var tieneEspecificar = $(element).attr('tieneEspecificar');
    if(valorSelect == "Otro" && tieneEspecificar!=1){
      $(element).after(modificarEnfermedadHTML)
      $(element).attr('tieneEspecificar',1)
    }else{
      $(element).parent().find(".tipo-especificar").remove();
      $(element).attr('tieneEspecificar',0)
    }
  }
</script>
<script src="{{ asset('/js/nuevocampo.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection    