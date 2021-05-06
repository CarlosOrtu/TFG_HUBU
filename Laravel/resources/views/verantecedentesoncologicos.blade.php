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
      <h1 class="align-self-center text-white panel-title">Antecedentes oncológicos</h1>
      <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
  </div>
  <table class="text-white table table-bordered">
      <tbody>
        @foreach($paciente->Antecedentes_oncologicos as $antecendete)
        <tr>
            <th>Antecedente oncológico {{ $loop->iteration }}</th>
            @if(preg_match("/^Otro: /", $antecendete->tipo))
            <td>{{ substr($antecendete->tipo, 6) }}</td>
            @else
            <td>{{ $antecendete->tipo }}</td>
            @endif
        </tr>
        @endforeach
      </tbody>
  </table>
</div>

@endsection
