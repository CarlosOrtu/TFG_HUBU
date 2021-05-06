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
  <?php 
  $numMaxEnfermedades = 0;
  foreach($paciente->Antecedentes_familiares as $antecendete){
    if($numMaxEnfermedades < count($antecendete->Enfermedades_familiar)){
      $numMaxEnfermedades = count($antecendete->Enfermedades_familiar);
    }
  }
  ?>
  <table class="text-white table table-bordered">
      <thead>
        <tr>
          <th></th>
          <th>Familiar</th>
          <th colspan="{{ $numMaxEnfermedades }}">Enfermedades</th>
        </tr>
      </thead>
      <tbody>
        @foreach($paciente->Antecedentes_familiares as $antecendete)
        <tr>
            <th>Antecedente familiar {{ $loop->iteration }}</th>
            @if(preg_match("/^Otro: /", $antecendete->familiar))
            <td>{{ substr($antecendete->familiar, 6) }}</td>
            @else
            <td>{{ $antecendete->familiar }}</td>
            @endif
            @foreach($antecendete->Enfermedades_familiar as $enfermedad)
              @if ($loop->last)
                @if(preg_match("/^Otro: /", $enfermedad->tipo))
                <td colspan="{{ $numMaxEnfermedades - $loop->index }}">{{ substr($enfermedad->tipo, 6) }}</td>
                @else
                <td colspan="{{ $numMaxEnfermedades - $loop->index }}">{{ $enfermedad->tipo }}</td>
                @endif
              @else
                @if(preg_match("/^Otro: /", $enfermedad->tipo))
                <td>{{ substr($enfermedad->tipo, 6) }}</td>
                @else
                <td>{{ $enfermedad->tipo }}</td>
                @endif
              @endif
            @endforeach
        </tr>
        @endforeach
      </tbody>
  </table>
</div>

@endsection
