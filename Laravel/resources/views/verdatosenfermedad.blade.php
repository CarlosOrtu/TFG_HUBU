@extends('layouts.app')

@section('content') 
<div class="col-md-11 pl-0">
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
  <table class="text-white table table-bordered">
      <tbody>
        <tr>
            <th>Fecha rimera consulta</th>
            <td>{{ $paciente->Enfermedades->fecha_primera_consulta }}</td>
        </tr>
        <tr>
            <th>Fecha diagnostico</th>
            <td>{{ $paciente->Enfermedades->fecha_diagnostico}}</td>
        </tr>
        <tr>
            <th>ECOG</th>
            <td>{{ $paciente->Enfermedades->ECOG }}</td>
        </tr>
        <tr>
            <th>T</th>
            <td>{{ $paciente->Enfermedades->T }}</td>
        </tr>
        <tr>
            <th>Tamaño T</th>
            <td>{{ $paciente->Enfermedades->T_tamano }}</td>
        </tr>
        <tr>
            <th>N</th>
            <td>{{ $paciente->Enfermedades->N }}</td>
        </tr>
        <tr>
            <th>Afectación N</th>
            <td>{{ $paciente->Enfermedades->N_afectacion }}</td>
        </tr>
        <tr>
            <th>M</th>
            <td>{{ $paciente->Enfermedades->M }}</td>
        </tr>
        <tr>
            <th>Número de afectación</th>
            <td>{{ $paciente->Enfermedades->num_afec_metas }}</td>
        </tr>
        <tr>
            <th>TNM</th>
            <td>{{ $paciente->Enfermedades->TNM }}</td>
        </tr>
        <tr>
            <th>Tipo de muestra</th>
            <td>{{ $paciente->Enfermedades->tipo_muestra }}</td>
        </tr>
        <tr>
            <th>Tipo de histología</th>
            <td>{{ $paciente->Enfermedades->histologia_tipo }}</td>
        </tr>
        <tr>
            <th>Subipo de histología</th>
            @if(preg_match("/^Otro: /", $paciente->Enfermedades->histologia_subtipo))
            <td>{{ substr($paciente->Enfermedades->histologia_subtipo, 6) }}</td>
            @else
            <td>{{ $paciente->Enfermedades->histologia_subtipo }}</td>
            @endif
        </tr>
        <tr>
            <th>Grado de histología</th>
            <td>{{ $paciente->Enfermedades->histologia_grado }}</td>
        </tr>
        <tr>
            <th>Tratamiento dirigido</th>
            @if($paciente->Enfermedades->tratamiento_dirigido == 1)
            <td>Si</td>
            @else
            <td>No</td>
            @endif
        </tr>
      </tbody>
  </table>
</div>

@endsection
