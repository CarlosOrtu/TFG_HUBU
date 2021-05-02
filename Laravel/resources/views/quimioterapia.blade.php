@extends('layouts.app')

@section('content') 
<div class="d-flex justify-content-between mb-4">
    @env('production')
    <h6 class="align-self-end text-white">Paciente: {{ $nombre }}</h6>
    @endenv
    @env('local')
    <h6 class="align-self-end text-white">Paciente: {{ $paciente->nombre }}</h6>
    @endenv
    <h1 class="align-self-center text-white panel-title">Quimioterapia</h1>
    <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@error('num_ciclos')
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@error('primer_ciclo')
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@error('ultimo_ciclo')
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@foreach ($paciente->Tratamientos->where('tipo','Quimioterapia') as $tratamiento)
<form action="{{ route('quimioterapiamodificar', ['id' => $paciente->id_paciente, 'num_quimioterapia' => $loop->index]) }}" method="post">
  @CSRF
  @method('put')
  <h4 class="text-white panel-title">Quimioterapia {{ $loop->iteration }}</h4>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Intención</span>
    </div>
    <select name="intencion" class="form-control">
      <option {{ $tratamiento->subtipo == 'Neoadyuvancia' ? 'selected' : '' }}>Neoadyuvancia</option>
      <option {{ $tratamiento->subtipo == 'Adyuvancia' ? 'selected' : '' }}>Adyuvancia</option>
      <option {{ $tratamiento->subtipo == 'Enfermedad avanzada' ? 'selected' : '' }}>Enfermedad avanzada</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Ensayo clínico</span>
    </div>
    <select name="ensayo_clinico" class="tipoTres form-control">
      <option {{ $tratamiento->Intenciones->ensayo != null ? 'selected' : '' }}>Si</option>
      <option {{ $tratamiento->Intenciones->ensayo == null ? 'selected' : '' }}>No</option>
    </select>
  </div>
  <div class="oculto ml-2 my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Tipo ensayo <br>clínico</span>
    </div>
    <select name="ensayo_clinico_tipo" class="form-control">
      <option {{ $tratamiento->Intenciones->ensayo == 'Observacional' ? 'selected' : '' }}>Observacional</option>
      <option {{ $tratamiento->Intenciones->ensayo == 'Experimental' ? 'selected' : '' }}>Experimental</option>
    </select>
  </div>
  <div class="oculto ml-2 my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Ensayo clínico <br>fase</span>
    </div>
    <select name="ensayo_clinico_fase" class="form-control">
      <option {{ $tratamiento->Intenciones->ensayo_fase == '1' ? 'selected' : '' }}>1</option>
      <option {{ $tratamiento->Intenciones->ensayo_fase == '2' ? 'selected' : '' }}>2</option>
      <option {{ $tratamiento->Intenciones->ensayo_fase == '3' ? 'selected' : '' }}>3</option>
      <option {{ $tratamiento->Intenciones->ensayo_fase == '4' ? 'selected' : '' }}>4</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Tratamiento por <br>acceso<br> expandido</span>
    </div>
    <select name="tratamiento_acceso" class="form-control">
      <option {{ $tratamiento->Intenciones->tratamiento_acceso_expandido == '1' ? 'selected' : '' }} value="1">Si</option>
      <option {{ $tratamiento->Intenciones->tratamiento_acceso_expandido == '0' ? 'selected' : '' }} value="0">No</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Tratamiento <br>fuera de<br> indicacion</span>
    </div>
    <select name="tratamiento_fuera" class="form-control">
      <option {{ $tratamiento->Intenciones->tratamiento_fuera_indicacion == '1' ? 'selected' : '' }} value="1">Si</option>
      <option {{ $tratamiento->Intenciones->tratamiento_fuera_indicacion == '0' ? 'selected' : '' }} value="0">No</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Esquema</span>
    </div>
    <select name="esquema" class="form-control">
      <option {{ $tratamiento->Intenciones->esquema == 'Monoterapia' ? 'selected' : '' }}>Monoterapia</option>
      <option {{ $tratamiento->Intenciones->esquema == 'Combinación' ? 'selected' : '' }}>Combinación</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Modo de <br>administración</span>
    </div>
    <select name="administracion" class="tipo form-control">
      <option {{ $tratamiento->Intenciones->modo_administracion == 'Oral' ? 'selected' : '' }}>Oral</option>
      <option {{ $tratamiento->Intenciones->modo_administracion == 'Intravenoso' ? 'selected' : '' }}>Intravenoso</option>
      <option {{ preg_match("/^Otro: /", $tratamiento->Intenciones->modo_administracion) ? 'selected' : '' }}>Otro</option>
    </select>
  </div>
  <div class="oculto ml-2 my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Especificar <br> modo de <br>administracion</span>
    </div>
      @if(preg_match("/^Otro: /", $tratamiento->Intenciones->tipo_farmaco))
      <input value="{{ substr($tratamiento->Intenciones->modo_administracion, 6) }}" name="especificar_administracion" class="form-control" autocomplete="off">
      @else
      <input name="especificar_administracion" class="form-control" autocomplete="off">
      @endif
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Tipo de <br>fármaco</span>
    </div>
    <select name="tipo_farmaco" class="tipo form-control">
      <option {{ $tratamiento->Intenciones->tipo_farmaco == 'Quimioterapia' ? 'selected' : '' }}>Quimioterapia</option>
      <option {{ $tratamiento->Intenciones->tipo_farmaco == 'Inmunoterapia' ? 'selected' : '' }}>Inmunoterapia</option>
      <option {{ $tratamiento->Intenciones->tipo_farmaco == 'Tratamiento dirigido' ? 'selected' : '' }}>Tratamiento dirigido</option>
      <option {{ $tratamiento->Intenciones->tipo_farmaco == 'Antiangiogénico' ? 'selected' : '' }}>Antiangiogénico</option>
      <option {{ $tratamiento->Intenciones->tipo_farmaco == 'Quimoterapia + Inmunoterapia' ? 'selected' : '' }}>Quimoterapia + Inmunoterapia</option>
      <option {{ $tratamiento->Intenciones->tipo_farmaco == 'Tratamiento dirigido' ? 'selected' : '' }}>Tratamiento dirigido</option>
      <option {{ $tratamiento->Intenciones->tipo_farmaco == 'Quimioterapia + Tratamiento dirigido' ? 'selected' : '' }}>Quimioterapia + Tratamiento dirigido</option>
      <option {{ $tratamiento->Intenciones->tipo_farmaco == 'Quimioterapia + Antiangiogénico' ? 'selected' : '' }}>Quimioterapia + Antiangiogénico</option>
      <option {{ preg_match("/^Otro: /", $tratamiento->Intenciones->tipo_farmaco) ? 'selected' : '' }}>Otro</option>
    </select>
  </div>
  <div class="oculto ml-2 my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Especificar <br>fármaco</span>
    </div>
      @if(preg_match("/^Otro: /", $tratamiento->Intenciones->tipo_farmaco))
      <input value="{{ substr($tratamiento->Intenciones->tipo_farmaco, 6) }}" name="especificar_tipo_farmaco" class="form-control" autocomplete="off">
      @else
      <input name="especificar_tipo_farmaco" class="form-control" autocomplete="off">
      @endif
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Número de <br>ciclos</span>
    </div>
    <input value="{{ $tratamiento->Intenciones->numero_ciclos }}" name="num_ciclos" type="number" step="1" min="1" class="form-control" autocomplete="off">
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Fecha del <br>primer ciclo</span>
    </div>
    <input value="{{ $tratamiento->fecha_inicio }}" name="primer_ciclo" type="date" class="form-control" autocomplete="off">
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Fecha del <br>último ciclo</span>
    </div>
    <input value="{{ $tratamiento->fecha_fin }}" name="ultimo_ciclo" type="date" class="form-control" autocomplete="off">
  </div>  
  @foreach ($tratamiento->Intenciones->Farmacos as $farmaco) 
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Fármacos</span>
    </div>
    <select name="farmacos[]" class="tipoDoble form-control">
      <option {{ $farmaco->tipo == 'Cisplatino' ? 'selected' : '' }}>Cisplatino</option>
      <option {{ $farmaco->tipo == 'Carboplatino' ? 'selected' : '' }}>Carboplatino</option>
      <option {{ $farmaco->tipo == 'Vinorelbina' ? 'selected' : '' }}>Vinorelbina</option>
      <option {{ $farmaco->tipo == 'Paclitaxel' ? 'selected' : '' }}>Paclitaxel</option>
      <option {{ $farmaco->tipo == 'Nab-paclitaxel' ? 'selected' : '' }}>Nab-paclitaxel</option>
      <option {{ $farmaco->tipo == 'Docetaxel' ? 'selected' : '' }}>Docetaxel</option>
      <option {{ $farmaco->tipo == 'Pemetrexed' ? 'selected' : '' }}>Pemetrexed</option>
      <option {{ $farmaco->tipo == 'Gemcitabina' ? 'selected' : '' }}>Gemcitabina</option>
      <option {{ $farmaco->tipo == 'Bevacizumab' ? 'selected' : '' }}>Bevacizumab</option>
      <option {{ $farmaco->tipo == 'Ramucirumab' ? 'selected' : '' }}>Ramucirumab</option>
      <option {{ $farmaco->tipo == 'Nintedanib' ? 'selected' : '' }}>Nintedanib</option>
      <option {{ $farmaco->tipo == 'Nivolumab' ? 'selected' : '' }}>Nivolumab</option>
      <option {{ $farmaco->tipo == 'Pembrolizumab' ? 'selected' : '' }}>Pembrolizumab</option>
      <option {{ $farmaco->tipo == 'Atezolizumab' ? 'selected' : '' }}>Atezolizumab</option>
      <option {{ $farmaco->tipo == 'Avelumab' ? 'selected' : '' }}>Avelumab</option>
      <option {{ $farmaco->tipo == 'Erlotinib' ? 'selected' : '' }}>Erlotinib</option>
      <option {{ $farmaco->tipo == 'Gefinitib' ? 'selected' : '' }}>Gefinitib</option>
      <option {{ $farmaco->tipo == 'Afatinib' ? 'selected' : '' }}>Afatinib</option>
      <option {{ $farmaco->tipo == 'Dacomitinib' ? 'selected' : '' }}>Dacomitinib</option>
      <option {{ $farmaco->tipo == 'Osimertinib' ? 'selected' : '' }} >Osimertinib</option>
      <option {{ $farmaco->tipo == 'Mobocertinib' ? 'selected' : '' }}>Mobocertinib</option>
      <option {{ $farmaco->tipo == 'Amivantamab' ? 'selected' : '' }}>Amivantamab</option>
      <option {{ $farmaco->tipo == 'Crizotinib' ? 'selected' : '' }}>Crizotinib</option>
      <option {{ $farmaco->tipo == 'Alectinib' ? 'selected' : '' }}>Alectinib</option>
      <option {{ $farmaco->tipo == 'Brigatinib' ? 'selected' : '' }}>Brigatinib</option>
      <option {{ $farmaco->tipo == 'Ceritinib' ? 'selected' : '' }}>Ceritinib</option>
      <option {{ $farmaco->tipo == 'Lorlatinib' ? 'selected' : '' }}>Lorlatinib</option>
      <option {{ $farmaco->tipo == 'Dabratinib' ? 'selected' : '' }}>Dabratinib</option>
      <option {{ $farmaco->tipo == 'Trametinib' ? 'selected' : '' }}>Trametinib</option>
      <option {{ $farmaco->tipo == 'Tepotinib' ? 'selected' : '' }}>Tepotinib</option>
      <option {{ $farmaco->tipo == 'Capmatinib' ? 'selected' : '' }}>Capmatinib</option>
      <option {{ $farmaco->tipo == 'Tepotinib' ? 'selected' : '' }}>Tepotinib</option>
      <option {{ $farmaco->tipo == 'Trastuzumab-deruxtecán' ? 'selected' : '' }}>Trastuzumab-deruxtecán</option>
      <option {{ $farmaco->tipo == 'Tepotinib' ? 'selected' : '' }}>Tepotinib</option>
      <option {{ preg_match("/^Ensayo: /", $farmaco->tipo) ? 'selected' : '' }}>Farmaco en ensayo clínico</option>
      <option {{ preg_match("/^Otro: /", $farmaco->tipo) ? 'selected' : '' }}>Otro</option>
    </select>
  </div>
  <div class="oculto ml-2 my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Especificar <br>fármaco</span>
    </div>
      @if(preg_match("/^Otro: /", $farmaco->tipo))
      <input value="{{ substr($farmaco->tipo, 6) }}" name="especificar_farmaco[]" class="form-control" autocomplete="off">
      @else
      <input name="especificar_farmaco[]" class="form-control" autocomplete="off">
      @endif
  </div>
  <div class="oculto ml-2 my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Especificar <br>fármaco de <br> ensayo clínico</span>
    </div>
      @if(preg_match("/^Ensayo: /", $farmaco->tipo))
      <input value="{{ substr($farmaco->tipo, 8) }}" name="especificar_farmaco_ensayo[]" class="form-control" autocomplete="off">
      @else
      <input name="especificar_farmaco_ensayo[]" class="form-control" autocomplete="off">
      @endif
  </div> 
  @endforeach
  <button type="button" class="btn-anadir btn btn-info">Añadir fármaco</button>
  <div class="d-flex justify-content-center">
      <button type="submit" class="btn btn-primary">Modificar</button>
</form>
      <form action="{{ route('quimioterapiaeliminar', ['id' => $paciente->id_paciente, 'num_quimioterapia' => $loop->index]) }}" method="post">
        @CSRF
        @method('delete')
        <button class="ml-2 btn btn-warning">Eliminar</button>
      </form>
    </div>
@endforeach 
<div class="mb-4 d-flex justify-content-strat">
    <button id="boton_nuevocampo" class="btn btn-info">Nueva quimioterapia</button>
</div>
<form id="nuevocampo" class="oculto" action="{{ route('quimioterapiacrear', ['id' => $paciente->id_paciente]) }}" method="post">
    @CSRF
    <h4 class="text-white panel-title">Familiar con antecedentes</h4>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Intención</span>
    </div>
    <select name="intencion" class="form-control">
      <option>Neoadyuvancia</option>
      <option>Adyuvancia</option>
      <option>Enfermedad avanzada</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Ensayo clínico</span>
    </div>
    <select name="ensayo_clinico" class="tipoTres form-control">
      <option>Si</option>
      <option>No</option>
    </select>
  </div>
  <div class="oculto ml-2 my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Tipo ensayo <br>clínico</span>
    </div>
    <select name="ensayo_clinico_tipo" class="form-control">
      <option>Observacional</option>
      <option>Experimental</option>
    </select>
  </div>
  <div class="oculto ml-2 my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Ensayo clínico <br>fase</span>
    </div>
    <select name="ensayo_clinico_fase" class="form-control">
      <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Tratamiento por <br>acceso<br> expandido</span>
    </div>
    <select name="tratamiento_acceso" class="form-control">
      <option value="1">Si</option>
      <option value="0">No</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Tratamiento <br>fuera de<br> indicacion</span>
    </div>
    <select name="tratamiento_fuera" class="form-control">
      <option value="1">Si</option>
      <option value="0">No</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Esquema</span>
    </div>
    <select name="esquema" class="form-control">
      <option>Monoterapia</option>
      <option>Combinación</option>
    </select>
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Modo de <br>administración</span>
    </div>
    <select name="administracion" class="tipo form-control">
      <option>Oral</option>
      <option>Intravenoso</option>
      <option>Otro</option>
    </select>
  </div>
  <div class="oculto ml-2 my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Especificar <br> modo de <br>administracion</span>
    </div>
    <input name="especificar_administracion" type="text" class="form-control" autocomplete="off">
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Número de <br>ciclos</span>
    </div>
    <input name="num_ciclos" type="number" step="1" min="1" class="form-control" autocomplete="off">
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Fecha del <br>primer ciclo</span>
    </div>
    <input name="primer_ciclo" type="date" class="form-control" autocomplete="off">
  </div>
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Fecha del <br>último ciclo</span>
    </div>
    <input name="ultimo_ciclo" type="date" class="form-control" autocomplete="off">
  </div>  
  <div class="my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Tipo de <br>fármaco</span>
    </div>
    <select name="tipo_farmaco" class="tipo form-control">
      <option>Quimioterapia</option>
      <option>Inmunoterapia</option>
      <option>Tratamiento dirigido</option>
      <option>Antiangiogénico</option>
      <option>Quimoterapia + Inmunoterapia</option>
      <option>Tratamiento dirigido</option>
      <option>Quimioterapia</option>
      <option>quimioterapia + Tratamiento dirigido</option>
      <option>Quimioterapia + Antiangiogénico</option>
      <option>Otro</option>
    </select>
  </div>
  <div class="oculto ml-2 my-4 input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Especificar <br>fármaco</span>
    </div>
    <input name="especificar_tipo_farmaco" type="text" class="form-control" autocomplete="off">
  </div>
  <button type="button" class="btn-anadir btn btn-info">Añadir farmaco</button>
  <div class="d-flex justify-content-center mb-4">
      <button type="submit" class="btn btn-primary">Guardar</button>
  </div>
</form>
<script type="text/javascript">
  $(document).ready(function(){
    //Definición de el html que se van a insertar
    var farmaco = '<div class="my-4 input-group"><div class="input-group-prepend"><span class="input-group-text">Fármacos</span></div><select onChange="anadir(this)" name="farmacos[]" class="tipo form-control"><option>Cisplatino</option><option>Carboplatino</option><option>Vinorelbina</option><option>Paclitaxel</option><option>Nab-paclitaxel</option><option>Docetaxel</option><option>Pemetrexed</option><option>Gemcitabina</option><option>Bevacizumab</option><option>Ramucirumab</option><option>Nintedanib</option><option>Nivolumab</option><option>Pembrolizumab</option><option>Atezolizumab</option><option>Avelumab</option><option>Erlotinib</option><option>Gefinitib</option><option>Afatinib</option><option>Dacomitinib</option><option>Osimertinib</option><option>Mobocertinib</option><option>Amivantamab</option><option>Crizotinib</option><option>Alectinib</option><option>Brigatinib</option><option>Ceritinib</option><option>Lorlatinib</option><option>Dabratinib</option><option>Trametinib</option><option>Tepotinib</option><option>Capmatinib</option><option>Tepotinib</option><option>Trastuzumab-deruxtecán</option><option>Tepotinib</option><option>Farmaco en ensayo clínico</option><option>Otro</option></select></div>'

    //Función que añade un nuevo tipo de cancer dentro del familiar
    $(".btn-anadir").click(function(){
      $(this).before(farmaco);
    });
  });
</script>
<script type="text/javascript">
  //Función que va a añadir el campo especificar tipo en el caso que el select sea Otro, en caso contrario lo oculta
  function anadir(element){
    var especificarFarmacoHTML = '<div id="prueba" class="tipo-especificar ml-2 mt-4 input-group"><div class="input-group-prepend"><span class="input-group-text">Especificar <br>fármaco</span></div><input name="especificar_farmaco[]" class="form-control" autocomplete="off"></div>'
    var especificarEnsayoHTML ='<div id="prueba2" class="tipo-especificar ml-2 mt-4 input-group"><div class="input-group-prepend"><span class="input-group-text">Especificar <br>fármaco de <br> ensayo clínico</span></div><input name="especificar_farmaco_ensayo[]" class="form-control" autocomplete="off"></div>'

    var valorSelect = $(element).val();
    var tieneEspecificar = $(element).attr('tieneEspecificar');
    if(valorSelect == "Otro"){
      $(element).parent().find(".tipo-especificar").remove();
      $(element).after(especificarFarmacoHTML)
    }else if(valorSelect == "Farmaco en ensayo clínico"){
      $(element).parent().find(".tipo-especificar").remove();
      $(element).after(especificarEnsayoHTML)
    }else{
      $(element).parent().find(".tipo-especificar").remove();
    }
  }
</script>
<script src="{{ asset('/js/nuevocampo.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection
