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
        <caption>Paciente</caption>
        <tbody>
        <tr>
            @if(env('APP_ENV') == 'production')
            <th scope="col">NHC</th>
            <td>{{ $nombre }}</td>
            @else
            <th scope="col">Nombre</th>
            <td>{{ $paciente->nombre }}</td>
            @endif
        </tr>
        @if(env('APP_ENV') == 'production')

        @else
        <tr>
            <th scope="col">Apellidos</th>
            <td>{{ $paciente->apellidos }}</td>
        </tr>
        @endif
        <tr>
            <th scope="col">Sexo</th>
            <td>{{ $paciente->sexo }}</td>
        </tr>
        <tr>
            <th scope="col">Nacimiento</th>
            <td>{{ $paciente->nacimiento }}</td>
        </tr>
        <tr>
            <th scope="col">Raza</th>
            <td>{{ $paciente->raza }}</td>
        </tr>
        <tr>
            <th scope="col">Profesión</th>
            @if(preg_match("/^Otro: /", $paciente->profesion))
            <td>{{ substr($paciente->profesion, 6) }}</td>
            @else
            <td>{{ $paciente->profesion }}</td>
            @endif
        </tr>
        <tr>
            <th scope="col">Fumador</th>
            <td>{{ $paciente->fumador }}</td>
        </tr>
        <tr>
            <th scope="col">Número de cigarros al día</th>
            <td>{{ $paciente->num_tabaco_dia }}</td>
        </tr>
        <tr>
            <th scope="col">Bebedor</th>
            <td>{{ $paciente->bebedor }}</td>
        </tr>
        <tr>
            <th scope="col">Carcinogenos</th>
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
