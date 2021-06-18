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
      <h1 class="align-self-center text-white panel-title">Metástasis</h1>
      <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
  </div>
  <table class="text-white table table-bordered">
      <caption>Metástasis</caption>
      <tbody>
        @foreach($paciente->Enfermedades->Metastasis as $metastasis)
        <tr>
            <th scope="col">Metástasis {{ $loop->iteration }}</th>
            @if(preg_match("/^Otro: /", $metastasis->tipo))
            <td>{{ substr($metastasis->tipo, 6) }}</td>
            @else
            <td>{{ $metastasis->tipo }}</td>
            @endif
        </tr>
        @endforeach
      </tbody>
  </table>
</div>

@endsection
