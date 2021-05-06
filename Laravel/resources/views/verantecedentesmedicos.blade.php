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
      <h1 class="align-self-center text-white panel-title">Antecedentes médicos</h1>
      <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
  </div>
  <table class="text-white table table-bordered">
      <tbody>
        @foreach($paciente->Antecedentes_medicos as $antecendete)
        <tr>
            <th>Antecedente médico {{ $loop->iteration }}</th>
            @if(preg_match("/^Otro: /", $antecendete->tipo_antecedente))
            <td>{{ substr($antecendete->tipo_antecedente, 6) }}</td>
            @else
            <td>{{ $antecendete->tipo_antecedente }}</td>
            @endif
        </tr>
        @endforeach
      </tbody>
  </table>
</div>

@endsection
