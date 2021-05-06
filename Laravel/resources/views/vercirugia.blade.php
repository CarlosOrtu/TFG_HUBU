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
      <h1 class="align-self-center text-white panel-title">Cirugía</h1>
      <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
  </div>
  <div class="table-responsive">
  <table class="text-white table table-bordered">
      <thead>
        <th></th>
        <th>Localización</th>
        <th>Fecha inicio</th>
      </thead>
      <tbody>
        @foreach($paciente->Tratamientos->where('tipo','Cirugia') as $cirugia)
        <tr>
            <th>Cirugia {{ $loop->iteration }}</th>
            <td>{{ $cirugia->subtipo }}</td>
            <td>{{ $cirugia->fecha_inicio }}</td>
        </tr>
        @endforeach
      </tbody>
  </table>
</div>
</div>

@endsection
