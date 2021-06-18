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
      <h1 class="align-self-center text-white panel-title">Seguimientos</h1>
      <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
  </div>
  <div class="table-responsive">
  <table class="text-white table table-bordered">
      <caption>Seguimientos</caption>
      <thead>
		    <th scope="col"></th>
        <th scope="col">Fecha seguimiento</th>
        <th scope="col">Estado</th>
        <th scope="col">Motivo del fallecimiento</th>
      </thead>
      <tbody>
        @foreach($paciente->seguimientos as $seguimiento)
        <tr>
            <th scope="col">Seguimiento {{ $loop->iteration }}</th>
            <td>{{ $seguimiento->fecha }}</td>
            <td>{{ $seguimiento->estado }}</td>
            @if(preg_match("/^Otro: /", $seguimiento->fallecido_motivo))
            <td>{{ substr($seguimiento->fallecido_motivo, 6) }}</td>
            @else
            <td>{{ $seguimiento->fallecido_motivo }}</td>
            @endif
        </tr>
        @endforeach
      </tbody>
  </table>
</div>
</div>

@endsection

