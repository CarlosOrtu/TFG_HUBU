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
      <h1 class="align-self-center text-white panel-title">Otros tumores</h1>
      <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
  </div>
  <table class="text-white table table-bordered">
      <tbody>
        @foreach($paciente->Enfermedades->Otros_tumores as $tumor)
        <tr>
            <th>Tumor {{ $loop->iteration }}</th>
            @if(preg_match("/^Otro: /", $tumor->tipo))
            <td>{{ substr($tumor->tipo, 6) }}</td>
            @else
            <td>{{ $tumor->tipo }}</td>
            @endif
        </tr>
        @endforeach
      </tbody>
  </table>
</div>

@endsection
