@extends('layouts.app')

@section('content') 
<div class="col-md-12 pl-0">
  <div class="d-flex justify-content-between mb-4">
      @env('production')
      <h6 class="align-self-end text-white">Paciente: {{ $nombre }}</h6>
      @endenv
      @env('local')
      <h6 class="align-self-end text-white">Paciente: {{ $paciente->nombre }}</h6>
      @endenv
      <h1 class="align-self-center text-white panel-title">Reevaluaciones</h1>
      <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
  </div>
  <div class="table-responsive">
  <table class="text-white table table-bordered">
      <thead>
		<th></th>
        <th>Fecha reevaluacion</th>
        <th>Estado</th>
        <th>Localización de la prograsión</th>
        <th>Tipo de tratamiento</th>
      </thead>
      <tbody>
        @foreach($paciente->Reevaluaciones as $reevaluacion)
        <tr>
            <th>Seguimiento {{ $loop->iteration }}</th>
            <td>{{ $reevaluacion->fecha }}</td>
            <td>{{ $reevaluacion->estado }}</td>
            @if(preg_match("/^Otro: /", $reevaluacion->progresion_localizacion))
            <td>{{ substr($reevaluacion->progresion_localizacion, 6) }}</td>
            @else
            <td>{{ $reevaluacion->progresion_localizacion }}</td>
            @endif
            <td>{{ $reevaluacion->tipo_tratamiento }}</td>
        </tr>
        @endforeach
      </tbody>
  </table>
</div>
</div>

@endsection

