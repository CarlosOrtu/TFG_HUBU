@extends('layouts.app')

@section('content') 
<form action="{{ route('datosenfermedad', ['id' => $paciente->id_paciente]) }}" method="post">
    @CSRF
    @method('put')
    <div class="d-flex justify-content-between mb-4">
        @env('production')
        <h6 class="align-self-end text-white">Paciente: {{ $nombre }}</h6>
        @endenv
        @env('local')
        <h6 class="align-self-end text-white">Paciente: {{ $paciente->nombre }}</h6>
        @endenv
        <h1 class="align-self-center text-white panel-title">Datos enfermedad</h1>
        <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
    </div>
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="text-dark  close" data-dismiss="alert">x</button>
        <strong class="text-center text-dark">{{ $message }}</strong>
    </div>
    @endif
    @if ($message = Session::get('SQLerror'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="text-dark close" data-dismiss="alert">x</button>
        <strong class="text-center text-dark">{{ $message }}</strong>
    </div>
    @endif
    <div class="row">
        <div class="col-md-5 pl-0">
            <div class="my-4 input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Fecha primera <br>consulta</span>
                </div>
                @if(!empty($paciente->enfermedades))
                <input value="{{ $paciente->enfermedades->fecha_primera_consulta }}" name="fecha_primera_consulta" type="date" class="form-control @error('fecha_primera_consulta') is-invalid @enderror" autocomplete="off">
                @else
                <input name="fecha_primera_consulta" type="date" class="form-control @error('fecha_primera_consulta') is-invalid @enderror" autocomplete="off">
                @endif
                @error('fecha_primera_consulta')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror  
            </div>
            <div class="my-4 input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Fecha <br> diagnostico</span>
                </div>
                @if(!empty($paciente->enfermedades))
                <input value="{{ $paciente->enfermedades->fecha_diagnostico}}" name="fecha_diagnostico" type="date" class="form-control @error('fecha_diagnostico') is-invalid @enderror" autocomplete="off">
                @else
                <input name="fecha_diagnostico" type="date" class="form-control @error('fecha_diagnostico') is-invalid @enderror" autocomplete="off">
                @endif
                @error('fecha_diagnostico')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror  
            </div>
            <div class="my-4 input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">ECOG</span>
                </div>
                @if(!empty($paciente->enfermedades))
                <select name="ECOG" class="form-control">
                  <option {{ $paciente->enfermedades->ECOG == 0 ? 'selected' : '' }}>0</option>
                  <option {{ $paciente->enfermedades->ECOG == 1 ? 'selected' : '' }}>1</option>
                  <option {{ $paciente->enfermedades->ECOG == 2 ? 'selected' : '' }}>2</option>
                  <option {{ $paciente->enfermedades->ECOG == 3 ? 'selected' : '' }}>3</option>
                  <option {{ $paciente->enfermedades->ECOG == 4 ? 'selected' : '' }}>4</option>
                </select>
                @else
                <select name="ECOG" class="form-control">
                  <option>0</option>
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                </select>
                @endif
            </div>
            <div class="my-4 input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">T</span>
                </div>
                @if(!empty($paciente->enfermedades))
                <select name="T" class="form-control">
                  <option {{ $paciente->enfermedades->T == 1 ? 'selected' : '' }}>1</option>
                  <option {{ $paciente->enfermedades->T == 2 ? 'selected' : '' }}>2</option>
                  <option {{ $paciente->enfermedades->T == 3 ? 'selected' : '' }}>3</option>
                  <option {{ $paciente->enfermedades->T == 4 ? 'selected' : '' }}>4</option>
                </select>
                @else
                <select name="T" class="form-control">
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                </select>
                @endif
            </div>
            <div class="my-4 input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Tamaño T</span>
                </div>
                @if(!empty($paciente->enfermedades))
                <input value="{{ $paciente->enfermedades->T_tamano }}" name="T_tamano" type="number" step="0.1" class="form-control @error('T_tamano') is-invalid @enderror" autocomplete="off">
                @else
                <input name="T_tamano" type="number" step="0.1" class="form-control @error('T_tamano') is-invalid @enderror" autocomplete="off">
                @endif
                @error('T_tamano')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror  
            </div>
            <div class="my-4 input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">N</span>
                </div>
                @if(!empty($paciente->enfermedades))
                <select name="N" class="form-control">
                  <option {{ $paciente->enfermedades->N == 1 ? 'selected' : '' }}>1</option>
                  <option {{ $paciente->enfermedades->N == 2 ? 'selected' : '' }}>2</option>
                  <option {{ $paciente->enfermedades->N == 3 ? 'selected' : '' }}>3</option>
                </select>
                @else
                <select name="N" class="form-control">
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                </select>
                @endif
            </div>
            <div class="my-4 input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Afectación N</span>
                </div>
                @if(!empty($paciente->enfermedades))
                <select name="N_afectacion" class="form-control">
                  <option {{ $paciente->enfermedades->N_afectacion == 'Uni ganglionar' ? 'selected' : '' }}>Uni ganglionar</option>
                  <option {{ $paciente->enfermedades->N_afectacion == 'Multiestación' ? 'selected' : '' }}>Multiestación</option>
                </select>
                @else
                <select name="N_afectacion" class="form-control">
                  <option>Uni ganglionar</option>
                  <option>Multiestación</option>
                </select>
                @endif
            </div>
            <div class="my-4 input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">M</span>
                </div>
                @if(!empty($paciente->enfermedades))
                <select name="M" class="form-control">
                  <option {{ $paciente->enfermedades->M == '0' ? 'selected' : '' }}>0</option>
                  <option {{ $paciente->enfermedades->M == '1a' ? 'selected' : '' }}>1a</option>
                  <option {{ $paciente->enfermedades->M == '1b' ? 'selected' : '' }}>1b</option>
                  <option {{ $paciente->enfermedades->M == '1c' ? 'selected' : '' }}>1c</option>
                </select>
                @else
                <select name="M" class="form-control">
                  <option>0</option>
                  <option>1a</option>
                  <option>1b</option>
                  <option>1c</option>
                </select>
                @endif 
            </div>
        </div>
        <div class="offset-md-1 col-md-5 pl-0">
            <div class="my-4 input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Número de <br>afectación</span>
                </div>
                @if(!empty($paciente->enfermedades))
                <select name="num_afec_metas" class="form-control">
                  <option {{ $paciente->enfermedades->num_afec_metas == '0' ? 'selected' : '' }}>0</option>
                  <option {{ $paciente->enfermedades->num_afec_metas == '1' ? 'selected' : '' }}>1</option>
                  <option {{ $paciente->enfermedades->num_afec_metas == '2-4' ? 'selected' : '' }}>2-4</option>
                  <option {{ $paciente->enfermedades->num_afec_metas == 'Mayor que 4' ? 'selected' : '' }}>Mayor que 4</option>
                </select>
                @else
                <select name="num_afec_metas" class="form-control">
                  <option>0</option>
                  <option>1</option>
                  <option>2-4</option>
                  <option>Mayor que 4</option>
                </select>
                @endif
            </div>
            <div class="my-4 input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">TNM</span>
                </div>
                @if(!empty($paciente->enfermedades))
                <select name="TNM" class="form-control">
                  <option {{ $paciente->enfermedades->TNM == 'IA1' ? 'selected' : '' }}>IA1</option>
                  <option {{ $paciente->enfermedades->TNM == 'IA2' ? 'selected' : '' }}>IA2</option>
                  <option {{ $paciente->enfermedades->TNM == 'IA3' ? 'selected' : '' }}>IA3</option>
                  <option {{ $paciente->enfermedades->TNM == 'IB' ? 'selected' : '' }}>IB</option>
                  <option {{ $paciente->enfermedades->TNM == 'IIA' ? 'selected' : '' }}>IIA</option>
                  <option {{ $paciente->enfermedades->TNM == 'IIB' ? 'selected' : '' }}>IIB</option>
                  <option {{ $paciente->enfermedades->TNM == 'IIIA' ? 'selected' : '' }}>IIIA</option>
                  <option {{ $paciente->enfermedades->TNM == 'IIIB' ? 'selected' : '' }}>IIIB</option>
                  <option {{ $paciente->enfermedades->TNM == 'IIIC' ? 'selected' : '' }}>IIIC</option>
                  <option {{ $paciente->enfermedades->TNM == 'IVa' ? 'selected' : '' }}>IVa</option>
                  <option {{ $paciente->enfermedades->TNM == 'IVb' ? 'selected' : '' }}>IVb</option>
                </select>
                @else
                <select name="TNM" class="form-control">
                  <option>IA1</option>
                  <option>IA2</option>
                  <option>IA3</option>
                  <option>IB</option>
                  <option>IIA</option>
                  <option>IIB</option>
                  <option>IIIA</option>
                  <option>IIIB</option>
                  <option>IIIC</option>
                  <option>IVa</option>
                  <option>IVb</option>
                </select>
                @endif 
            </div>
            <div class="my-4 input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Tipo de <br>muestra</span>
                </div>
                @if(!empty($paciente->enfermedades))
                <select name="tipo_muestra" class="form-control">
                  <option {{ $paciente->enfermedades->tipo_muestra == 'Citología' ? 'selected' : '' }}>Citología</option>
                  <option {{ $paciente->enfermedades->tipo_muestra == 'Biopsia' ? 'selected' : '' }}>Biopsia</option>
                  <option {{ $paciente->enfermedades->tipo_muestra == 'Bloque celular desde citología' ? 'selected' : '' }}>Bloque celular desde citología</option>
                </select>
                @else
                <select name="tipo_muestra" class="form-control">
                  <option>Citología</option>
                  <option>Biopsia</option>
                  <option>Bloque celular desde citología</option>
                </select>
                @endif
            </div>
            <div class="my-4 input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Tipo de <br>histología</span>
                </div>
                @if(!empty($paciente->enfermedades))
                <select name="histologia_tipo" class="form-control">
                  <option {{ $paciente->enfermedades->histologia_tipo == 'Adenocarcinoma' ? 'selected' : '' }}>Adenocarcinoma</option>
                  <option {{ $paciente->enfermedades->histologia_tipo == 'Epidermoide' ? 'selected' : '' }}>Epidermoide</option>
                  <option {{ $paciente->enfermedades->histologia_tipo == 'Adenoescamoso' ? 'selected' : '' }}>Adenoescamoso</option>
                  <option {{ $paciente->enfermedades->histologia_tipo == 'Carcinoma de células grandes' ? 'selected' : '' }}>Carcinoma de células grandes</option>
                  <option {{ $paciente->enfermedades->histologia_tipo == 'Sarcomatoide' ? 'selected' : '' }}>Sarcomatoide</option>
                  <option {{ $paciente->enfermedades->histologia_tipo == 'Indiferenciado' ? 'selected' : '' }}>Indiferenciado</option>
                </select>
                @else
                <select name="histologia_tipo" class="form-control">
                  <option>Adenocarcinoma</option>
                  <option>Epidermoide</option>
                  <option>Adenoescamoso</option>
                  <option>Carcinoma de células grandes</option>
                  <option>Sarcomatoide</option>
                  <option>Indiferenciado</option>
                </select>
                @endif
            </div>
            <div class="my-4 input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Subtipo de <br>histología</span>
                </div>
                @if(!empty($paciente->enfermedades))
                <select id="histologia_subtipo" name="histologia_subtipo" class="tipo @error('histologia_subtipo_especificar') is-invalid @enderror form-control">
                  <option {{ $paciente->enfermedades->histologia_subtipo == 'Desconocido' ? 'selected' : '' }}>Desconocido</option>
                  <option {{ $paciente->enfermedades->histologia_subtipo == 'Acinar' ? 'selected' : '' }}>Acinar</option>
                  <option {{ $paciente->enfermedades->histologia_subtipo == 'Lepidico' ? 'selected' : '' }}>Lepidico</option>
                  <option {{ $paciente->enfermedades->histologia_subtipo == 'Papilar' ? 'selected' : '' }}>Papilar</option>
                  <option {{ $paciente->enfermedades->histologia_subtipo == 'Micropapilar' ? 'selected' : '' }}>Micropapilar</option>
                  <option {{ $paciente->enfermedades->histologia_subtipo == 'Solido' ? 'selected' : '' }}>Solido</option>
                  <option {{ $paciente->enfermedades->histologia_subtipo == 'Mucinoso' ? 'selected' : '' }}>Mucinoso</option>
                  <option {{ $paciente->enfermedades->histologia_subtipo == 'Celulas claras' ? 'selected' : '' }}>Celulas claras</option>
                  <option {{ !in_array($paciente->enfermedades->histologia_subtipo, ['Desconocido','Acinar','Lepidico','Papilar','Micropapilar','Solido','Mucinoso','Celulas claras']) ? 'selected' : '' }}>Otro</option>
                </select>
                @else
                <select name="histologia_subtipo" class="tipo @error('histologia_subtipo_especificar') is-invalid @enderror form-control">
                  <option>Desconocido</option>
                  <option>Acinar</option>
                  <option>Lepidico</option>
                  <option>Papilar</option>
                  <option>Micropapilar</option>
                  <option>Solido</option>
                  <option>Mucinoso</option>
                  <option>Celulas claras</option>
                  <option>Otro</option>
                </select>
                @endif
                @error('histologia_subtipo_especificar')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror  
            </div>
            <div id="especificar" class="oculto ml-4 my-4 input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Especificar <br>subtipo</span>
                </div>
                @if(!empty($paciente->enfermedades))
                  @if(!in_array($paciente->enfermedades->histologia_subtipo, ['Desconocido','Acinar','Lepidico','Papilar','Micropapilar','Solido','Mucinoso','Celulas claras']))
                  <input value="{{ substr($paciente->enfermedades->histologia_subtipo, 6) }}" name="histologia_subtipo_especificar" class="form-control @error('nombre') is-invalid @enderror" autocomplete="off">
                  @else
                  <input name="histologia_subtipo_especificar" class="form-control @error('nombre') is-invalid @enderror" autocomplete="off">
                  @endif
                @else
                <input name="histologia_subtipo_especificar" class="form-control @error('nombre') is-invalid @enderror" autocomplete="off">  
                @endif
            </div>
            <div class="my-4 input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Grado de <br>histología</span>
                </div>
                @if(!empty($paciente->enfermedades))
                <select name="histologia_grado" class="form-control">
                  <option {{ $paciente->enfermedades->histologia_grado == 'Bien diferenciado' ? 'selected' : '' }}>Bien diferenciado</option>
                  <option {{ $paciente->enfermedades->histologia_grado == 'Moderadamente diferenciado' ? 'selected' : '' }}>Moderadamente diferenciado</option>
                  <option {{ $paciente->enfermedades->histologia_grado == 'Mal diferenciado' ? 'selected' : '' }}>Mal diferenciado</option>
                  <option {{ $paciente->enfermedades->histologia_grado == 'No especificado' ? 'selected' : '' }}>No especificado</option>
                </select>
                @else
                <select name="histologia_grado" class="form-control">
                  <option>Bien diferenciado</option>
                  <option>Moderadamente diferenciado</option>
                  <option>Mal diferenciado</option>
                  <option>No especificado</option>
                </select>
                @endif
            </div>
            <div class="my-4 input-group">
              <div class="input-group-prepend">
                  <span class="input-group-text">Tratamiento <br>dirigido</span>
              </div>
              <select name="tratamiento_dirigido" class="tipo form-control">
                @if(!empty($paciente->enfermedades))
                <option {{ $paciente->enfermedades->tratamiento_dirigido == 1 ? 'selected' : '' }} value="1">Si</option>
                <option {{ $paciente->enfermedades->tratamiento_dirigido == 0 ? 'selected' : '' }} value="0">No</option>
                @else
                <option value="1">Si</option>
                <option value="0">No</option>
                @endif
              </select>      
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <button id="boton_crearpaciente" type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection
