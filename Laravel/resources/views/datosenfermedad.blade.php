@extends('layouts.app')

@section('content') 
<form action="{{ route('datosenfermedad', ['id' => $paciente->id_paciente]) }}" method="post">
    @CSRF
    @method('put')
    <h1 class="text-white text-center panel-title">Datos enfermedad</h1>
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="text-white close" data-dismiss="alert">x</button>
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
                @if(!empty($paciente->enfermedad))
                <input value="{{ $paciente->enfermedad->fecha_primera_consulta }}" name="fecha_primera_consulta" type="date" class="form-control @error('fecha_primera_consulta') is-invalid @enderror" autocomplete="off">
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
                @if(!empty($paciente->enfermedad))
                <input value="{{ $paciente->enfermedad->fecha_diagnostico}}" name="fecha_diagnostico" type="date" class="form-control @error('fecha_diagnostico') is-invalid @enderror" autocomplete="off">
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
                @if(!empty($paciente->enfermedad))
                <select name="ECOG" class="form-control">
                  <option {{ $paciente->enfermedad->ECOG == 0 ? 'selected' : '' }}>0</option>
                  <option {{ $paciente->enfermedad->ECOG == 1 ? 'selected' : '' }}>1</option>
                  <option {{ $paciente->enfermedad->ECOG == 2 ? 'selected' : '' }}>2</option>
                  <option {{ $paciente->enfermedad->ECOG == 3 ? 'selected' : '' }}>3</option>
                  <option {{ $paciente->enfermedad->ECOG == 4 ? 'selected' : '' }}>4</option>
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
                @if(!empty($paciente->enfermedad))
                <select name="T" class="form-control">
                  <option {{ $paciente->enfermedad->T == 1 ? 'selected' : '' }}>1</option>
                  <option {{ $paciente->enfermedad->T == 2 ? 'selected' : '' }}>2</option>
                  <option {{ $paciente->enfermedad->T == 3 ? 'selected' : '' }}>3</option>
                  <option {{ $paciente->enfermedad->T == 4 ? 'selected' : '' }}>4</option>
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
                @if(!empty($paciente->enfermedad))
                <input value="{{ $paciente->enfermedad->T_tamano }}" name="T_tamano" type="number" step="0.1" class="form-control @error('T_tamano') is-invalid @enderror" autocomplete="off">
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
                @if(!empty($paciente->enfermedad))
                <select name="N" class="form-control">
                  <option {{ $paciente->enfermedad->N == 1 ? 'selected' : '' }}>1</option>
                  <option {{ $paciente->enfermedad->N == 2 ? 'selected' : '' }}>2</option>
                  <option {{ $paciente->enfermedad->N == 3 ? 'selected' : '' }}>3</option>
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
                @if(!empty($paciente->enfermedad))
                <select name="N_afectacion" class="form-control">
                  <option {{ $paciente->enfermedad->N_afectacion == 'Uni ganglionar' ? 'selected' : '' }}>Uni ganglionar</option>
                  <option {{ $paciente->enfermedad->N_afectacion == 'Multiestación' ? 'selected' : '' }}>Multiestación</option>
                  <option {{ $paciente->enfermedad->N_afectacion == 3 ? 'selected' : '' }}>3</option>
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
                @if(!empty($paciente->enfermedad))
                <select name="M" class="form-control">
                  <option {{ $paciente->enfermedad->M == '0' ? 'selected' : '' }}>0</option>
                  <option {{ $paciente->enfermedad->M == '1a' ? 'selected' : '' }}>1a</option>
                  <option {{ $paciente->enfermedad->M == '1b' ? 'selected' : '' }}>1b</option>
                  <option {{ $paciente->enfermedad->M == '1c' ? 'selected' : '' }}>1c</option>
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
                @if(!empty($paciente->enfermedad))
                <select name="num_afec_metas" class="form-control">
                  <option {{ $paciente->enfermedad->num_afec_metas == '0' ? 'selected' : '' }}>0</option>
                  <option {{ $paciente->enfermedad->num_afec_metas == '1' ? 'selected' : '' }}>1</option>
                  <option {{ $paciente->enfermedad->num_afec_metas == '2-4' ? 'selected' : '' }}>2-4</option>
                  <option {{ $paciente->enfermedad->num_afec_metas == 'Mayor que 4' ? 'selected' : '' }}>Mayor que 4</option>
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
                @if(!empty($paciente->enfermedad))
                <select name="TNM" class="form-control">
                  <option {{ $paciente->enfermedad->TNM == 'IA1' ? 'selected' : '' }}>IA1</option>
                  <option {{ $paciente->enfermedad->TNM == 'IA2' ? 'selected' : '' }}>IA2</option>
                  <option {{ $paciente->enfermedad->TNM == 'IA3' ? 'selected' : '' }}>IA3</option>
                  <option {{ $paciente->enfermedad->TNM == 'IB' ? 'selected' : '' }}>IB</option>
                  <option {{ $paciente->enfermedad->TNM == 'IIA' ? 'selected' : '' }}>IIA</option>
                  <option {{ $paciente->enfermedad->TNM == 'IIB' ? 'selected' : '' }}>IIB</option>
                  <option {{ $paciente->enfermedad->TNM == 'IIIA' ? 'selected' : '' }}>IIIA</option>
                  <option {{ $paciente->enfermedad->TNM == 'IIIB' ? 'selected' : '' }}>IIIB</option>
                  <option {{ $paciente->enfermedad->TNM == 'IIIC' ? 'selected' : '' }}>IIIC</option>
                  <option {{ $paciente->enfermedad->TNM == 'IVa' ? 'selected' : '' }}>IVa</option>
                  <option {{ $paciente->enfermedad->TNM == 'IVb' ? 'selected' : '' }}>IVb</option>
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
                @if(!empty($paciente->enfermedad))
                <select name="tipo_muestra" class="form-control">
                  <option {{ $paciente->enfermedad->tipo_muestra == 'Citología' ? 'selected' : '' }}>Citología</option>
                  <option {{ $paciente->enfermedad->tipo_muestra == 'Biopsia' ? 'selected' : '' }}>Biopsia</option>
                  <option {{ $paciente->enfermedad->tipo_muestra == 'Bloque celular desde citología' ? 'selected' : '' }}>Bloque celular desde citología</option>
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
                @if(!empty($paciente->enfermedad))
                <select name="histologia_tipo" class="form-control">
                  <option {{ $paciente->enfermedad->histologia_tipo == 'Adenocarcinoma' ? 'selected' : '' }}>Adenocarcinoma</option>
                  <option {{ $paciente->enfermedad->histologia_tipo == 'Epidermoide' ? 'selected' : '' }}>Epidermoide</option>
                  <option {{ $paciente->enfermedad->histologia_tipo == 'Adenoescamoso' ? 'selected' : '' }}>Adenoescamoso</option>
                  <option {{ $paciente->enfermedad->histologia_tipo == 'Carcinoma de células grandes' ? 'selected' : '' }}>Carcinoma de células grandes</option>
                  <option {{ $paciente->enfermedad->histologia_tipo == 'Sarcomatoide' ? 'selected' : '' }}>Sarcomatoide</option>
                  <option {{ $paciente->enfermedad->histologia_tipo == 'Indiferenciado' ? 'selected' : '' }}>Indiferenciado</option>
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
                @if(!empty($paciente->enfermedad))
                <select id="histologia_subtipo" name="histologia_subtipo" class="@error('histologia_subtipo_especificar') is-invalid @enderror form-control">
                  <option {{ $paciente->enfermedad->histologia_subtipo == 'Desconocido' ? 'selected' : '' }}>Desconocido</option>
                  <option {{ $paciente->enfermedad->histologia_subtipo == 'Acinar' ? 'selected' : '' }}>Acinar</option>
                  <option {{ $paciente->enfermedad->histologia_subtipo == 'Lepidico' ? 'selected' : '' }}>Lepidico</option>
                  <option {{ $paciente->enfermedad->histologia_subtipo == 'Papilar' ? 'selected' : '' }}>Papilar</option>
                  <option {{ $paciente->enfermedad->histologia_subtipo == 'Micropapilar' ? 'selected' : '' }}>Micropapilar</option>
                  <option {{ $paciente->enfermedad->histologia_subtipo == 'Solido' ? 'selected' : '' }}>Solido</option>
                  <option {{ $paciente->enfermedad->histologia_subtipo == 'Mucinoso' ? 'selected' : '' }}>Mucinoso</option>
                  <option {{ $paciente->enfermedad->histologia_subtipo == 'Celulas claras' ? 'selected' : '' }}>Celulas claras</option>
                  <option {{ !in_array($paciente->enfermedad->histologia_subtipo, ['Acinar','Lepidico','Papilar','Micropapilar','Solido','Mucinoso','Celulas claras']) ? 'selected' : '' }}>Otro</option>
                </select>
                @else
                <select id="histologia_subtipo" name="histologia_subtipo" class="@error('histologia_subtipo_especificar') is-invalid @enderror form-control">
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
            <div id="especificar" class="ml-4 my-4 input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Especificar <br>subtipo</span>
                </div>
                @if(!empty($paciente->enfermedad))
                  @if(!in_array($paciente->enfermedad->histologia_subtipo, ['Acinar','Lepidico','Papilar','Micropapilar','Solido','Mucinoso','Celulas claras']))
                  <input value="{{ $paciente->enfermedad->histologia_subtipo }}" name="histologia_subtipo_especificar" class="form-control @error('nombre') is-invalid @enderror" autocomplete="off">
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
                @if(!empty($paciente->enfermedad))
                <select name="histologia_grado" class="form-control">
                  <option {{ $paciente->enfermedad->histologia_grado == 'Bien diferenciado' ? 'selected' : '' }}>Bien diferenciado</option>
                  <option {{ $paciente->enfermedad->histologia_grado == 'Moderadamente diferenciado' ? 'selected' : '' }}>Moderadamente diferenciado</option>
                  <option {{ $paciente->enfermedad->histologia_grado == 'Mal diferenciado' ? 'selected' : '' }}>Mal diferenciado</option>
                  <option {{ $paciente->enfermedad->histologia_grado == 'No especificado' ? 'selected' : '' }}>No especificado</option>
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
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <button id="boton_crearpaciente" type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
    var subtipo = $('#histologia_subtipo').val();
    if( subtipo == "Otro") {
        $('#especificar').show();
    }else{
        $('#especificar').hide();
    }
    $( "#histologia_subtipo" ).change(function() {
        var subtipo = $('#histologia_subtipo').val();
        if( subtipo == "Otro") {
            $('#especificar').show();
        }else{
            $('#especificar').hide();
        }
    });
});
</script>
@endsection
