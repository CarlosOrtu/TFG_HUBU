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
      <h1 class="align-self-center text-white panel-title">Radioterapia</h1>
      <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
  </div>
  <div class="table-responsive">
  <table class="text-white table table-bordered">
      <thead>
        <th></th>
        <th>Tipo</th>
        <th>Localización</th>
        <th>Dosis</th>
        <th>Fecha inicio</th>
        <th>Fecha fin</th>
      </thead>        
      <tbody>
        @foreach($paciente->Tratamientos->where('tipo','Radioterapia') as $radioterapia)
        <tr>
            <th>Quimioterapia {{ $loop->iteration }}</th>
            <td>{{ $radioterapia->subtipo }}</td>
            @if(preg_match("/^Otro: /", $radioterapia->localizacion))
            <td>{{ substr($radioterapia->localizacion, 6) }}</td>
            @else
            <td>{{ $radioterapia->localizacion }}</td>
            @endif
            <td>{{ $radioterapia->dosis }}</td>
            <td>{{ $radioterapia->fecha_inicio }}</td>
            <td>{{ $radioterapia->fecha_fin }}</td>
        </tr>
        @endforeach
      </tbody>
  </table>
</div>
</div>

@endsection
