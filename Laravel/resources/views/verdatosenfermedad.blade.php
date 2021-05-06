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
            <td>{{ $paciente->enfermedad->fecha_primera_consulta }}</td>
        </tr>
        <tr>
            <th>Fecha diagnostico</th>
            <td>{{ $paciente->enfermedad->fecha_diagnostico}}</td>
        </tr>
        <tr>
            <th>ECOG</th>
            <td>{{ $paciente->enfermedad->ECOG }}</td>
        </tr>
        <tr>
            <th>T</th>
            <td>{{ $paciente->enfermedad->T }}</td>
        </tr>
        <tr>
            <th>Tamaño T</th>
            <td>{{ $paciente->enfermedad->T_tamano }}</td>
        </tr>
        <tr>
            <th>N</th>
            <td>{{ $paciente->enfermedad->N }}</td>
        </tr>
        <tr>
            <th>Afectación N</th>
            <td>{{ $paciente->enfermedad->N_afectacion }}</td>
        </tr>
        <tr>
            <th>M</th>
            <td>{{ $paciente->enfermedad->M }}</td>
        </tr>
        <tr>
            <th>Número de afectación</th>
            <td>{{ $paciente->enfermedad->num_afec_metas }}</td>
        </tr>
        <tr>
            <th>TNM</th>
            <td>{{ $paciente->enfermedad->TNM }}</td>
        </tr>
        <tr>
            <th>Tipo de muestra</th>
            <td>{{ $paciente->enfermedad->tipo_muestra }}</td>
        </tr>
        <tr>
            <th>Tipo de histología</th>
            <td>{{ $paciente->enfermedad->histologia_tipo }}</td>
        </tr>
        <tr>
            <th>Subipo de histología</th>
            @if(preg_match("/^Otro: /", $paciente->enfermedad->histologia_subtipo))
            <td>{{ substr($paciente->enfermedad->histologia_subtipo, 6) }}</td>
            @else
            <td>{{ $paciente->enfermedad->histologia_subtipo }}</td>
            @endif
        </tr>
        <tr>
            <th>Grado de histología</th>
            <td>{{ $paciente->enfermedad->histologia_grado }}</td>
        </tr>
        <tr>
            <th>Tratamiento dirigido</th>
            @if($paciente->enfermedad->tratamiento_dirigido == 1)
            <td>Si</td>
            @else
            <td>No</td>
            @endif
        </tr>
      </tbody>
  </table>
</div>

@endsection
