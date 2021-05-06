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
      <h1 class="align-self-center text-white panel-title">Biomarcadores</h1>
      <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
  </div>
  <table class="text-white table table-bordered">
      <tbody>
        @foreach($paciente->Enfermedad->Biomarcadores as $biomarcador)
        <tr>
            <th>Biomarcador {{ $loop->iteration }}</th>
            <td>{{ $biomarcador->nombre }}
            @if(preg_match("/^Otro: /", $biomarcador->tipo))
            <td>{{ substr($biomarcador->tipo, 6) }}</td>
            @else
            <td>{{ $biomarcador->tipo }}</td>
            @endif
            @if(preg_match("/^Otro: /", $biomarcador->subtipo))
            <td>{{ substr($biomarcador->subtipo, 6) }}</td>
            @else
            <td>{{ $biomarcador->subtipo }}</td>
            @endif
        </tr>
        @endforeach
      </tbody>
  </table>
</div>

@endsection