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
        <h1 class="align-self-center text-white panel-title">Datos paciente</h1>
        <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
    </div>
    <table class="text-white table table-bordered">
        <tbody>
        <tr>
            <th>NHC</th>
            @if(env('APP_ENV') == 'production')
            <td>{{ $nombre }}</td>
            @else
            <td>{{ $paciente->nombre }}</td>
            @endif
        </tr>
        @if(env('APP_ENV') == 'production')

        @else
        <tr>
            <th>Apellidos</th>
            @if(env('APP_ENV') == 'production')
            <td>{{ $apellidos }}</td>
            @else
            <td>{{ $paciente->apellidos }}</td>
            @endif
        </tr>
        @endif
        <tr>
            <th>Sexo</th>
            <td>{{ $paciente->sexo }}</td>
        </tr>
        <tr>
            <th>Nacimiento</th>
            <td>{{ $paciente->nacimiento }}</td>
        </tr>
        <tr>
            <th>Raza</th>
            <td>{{ $paciente->raza }}</td>
        </tr>
        <tr>
            <th>Profesión</th>
            @if(preg_match("/^Otro: /", $paciente->profesion))
            <td>{{ substr($paciente->profesion, 6) }}</td>
            @else
            <td>{{ $paciente->profesion }}</td>
            @endif
        </tr>
        <tr>
            <th>Fumador</th>
            <td>{{ $paciente->fumador }}</td>
        </tr>
        <tr>
            <th>Número de cigarros al día</th>
            <td>{{ $paciente->num_tabaco_dia }}</td>
        </tr>
        <tr>
            <th>Bebedor</th>
            <td>{{ $paciente->bebedor }}</td>
        </tr>
        <tr>
            <th>Carcinogenos</th>
            @if(preg_match("/^Otro: /", $paciente->carcinogenos))
            <td>{{ substr($paciente->carcinogenos, 6) }}</td>
            @else
            <td>{{ $paciente->carcinogenos }}</td>
            @endif
        </tr>
        </tbody>
    </table>
</div>
@endsection
