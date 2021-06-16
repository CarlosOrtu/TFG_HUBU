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
      <h1 class="align-self-center text-white panel-title">Datos síntomas</h1>
      <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
  </div>
  <table class="text-white table table-bordered">
      <tbody>
        <tr>
            @if($paciente->Enfermedades->Sintomas->first() != null)
            <th>Fecha inicio sintomas</th>
            <td>{{ $paciente->Enfermedades->Sintomas->first()->fecha_inicio }}</td>
            @endif
        </tr>
        @foreach($paciente->Enfermedades->Sintomas as $sintoma)
        <tr>
            <th>Síntoma {{ $loop->iteration }}</th>
            @if(preg_match("/^Otro: /", $sintoma->tipo))
            <td>{{ substr($sintoma->tipo, 6) }}</td>
            @else
            <td>{{ $sintoma->tipo }}</td>
            @endif
        </tr>
        @endforeach
      </tbody>
  </table>
</div>

@endsection
