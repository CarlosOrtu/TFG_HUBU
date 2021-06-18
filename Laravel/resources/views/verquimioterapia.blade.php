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
      <h1 class="align-self-center text-white panel-title">Quimioterapia</h1>
      <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
  </div>
  <?php 
  $numMaxFarmacos = 0;
  foreach($paciente->Tratamientos->where('tipo','Quimioterapia') as $quimioterapia){
    if($numMaxFarmacos < count($quimioterapia->Intenciones->Farmacos)){
      $numMaxFarmacos = count($quimioterapia->Intenciones->Farmacos);
    }
  }
  ?>
  <div class="table-responsive">
  <table class="text-white table table-bordered">
      <caption>Quimioterapias</caption>
      <thead>
        <th scope="col"></th>
        <th scope="col">Tipo</th>
        <th scope="col">Ensayo</th>
        <th scope="col">Ensayo fase</th>
        <th scope="col">Acceso expandido</th>
        <th scope="col">Fuera de indicación</th>
        <th scope="col">Medicación extranjera</th>
        <th scope="col">Esquema</th>
        <th scope="col">Administración</th>
        <th scope="col">Tipo farmaco</th>
        <th scope="col">Ciclos</th>
        <th scope="col">Fecha inicio</th>
        <th scope="col">Fecha fin</th>
        <th scope="col" colspan="{{ $numMaxFarmacos }}">Farmacos</th>
      </thead>
      <tbody>
        @foreach($paciente->Tratamientos->where('tipo','Quimioterapia') as $quimioterapia)
        <tr>
            <th scope="col">Quimioterapia {{ $loop->iteration }}</th>
            <td>{{ $quimioterapia->subtipo }}</td>
            <td>{{ $quimioterapia->Intenciones->ensayo }}</td>
            <td>{{ $quimioterapia->Intenciones->ensayo_fase }}</td>
            @if($quimioterapia->Intenciones->tratamiento_acceso_expandido == 1)
            <td>Si</td>
            @else
            <td>No</td>
            @endif
            @if($quimioterapia->Intenciones->tratamiento_fuera_indicacion == 1)
            <td>Si</td>
            @else
            <td>No</td>
            @endif
            @if($quimioterapia->Intenciones->medicacion_extranjera == 1)
            <td>Si</td>
            @else
            <td>No</td>
            @endif
            <td>{{ $quimioterapia->Intenciones->esquema }}</td>
            @if(preg_match("/^Otro: /", $quimioterapia->Intenciones->modo_administracion))
            <td>{{ substr($quimioterapia->Intenciones->modo_administracion, 6) }}</td>
            @else
            <td>{{ $quimioterapia->Intenciones->modo_administracion }}</td>
            @endif
            @if(preg_match("/^Otro: /", $quimioterapia->Intenciones->tipo_farmaco))
            <td>{{ substr($quimioterapia->Intenciones->tipo_farmaco, 6) }}</td>
            @else
            <td>{{ $quimioterapia->Intenciones->tipo_farmaco }}</td>
            @endif
            <td>{{ $quimioterapia->Intenciones->numero_ciclos }}</td>
            <td>{{ $quimioterapia->fecha_inicio }}</td>
            <td>{{ $quimioterapia->fecha_fin }}</td>
            @foreach($quimioterapia->Intenciones->Farmacos as $farmaco)
            @if ($loop->last)
              @if(preg_match("/^Otro: /", $farmaco->tipo))
              <td colspan="{{ $numMaxFarmacos - $loop->index }}">{{ substr($farmaco->tipo, 6) }}</td>
              @elseif((preg_match("/^Ensayo: /", $farmaco->tipo)))
              <td colspan="{{ $numMaxFarmacos - $loop->index }}">{{ substr($farmaco->tipo, 8) }}</td>
              @else
              <td colspan="{{ $numMaxFarmacos - $loop->index }}">{{ $farmaco->tipo }}</td>
              @endif
            @else
              @if(preg_match("/^Otro: /", $farmaco->tipo))
              <td>{{ substr($farmaco->tipo, 6) }}</td>
              @elseif((preg_match("/^Ensayo: /", $farmaco->tipo)))
              <td>{{ substr($farmaco->tipo, 8) }}</td>
              @else              
              <td>{{ $farmaco->tipo }}</td>
              @endif
            @endif
            @endforeach
        </tr>
        @endforeach
      </tbody>
  </table>
</div>
</div>

@endsection
