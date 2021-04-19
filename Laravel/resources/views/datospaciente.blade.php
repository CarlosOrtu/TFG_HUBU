@extends('layouts.app')

@section('content')
<form action="{{ route('datospaciente', ['id' => $paciente->id_paciente]) }}" method="post">
    @CSRF
    @method('put')
    <div class="col-md-11 pl-0">
        <h1 class="text-white text-center panel-title">Datos personales</h1>
        @if ($message = Session::get('SQLerror'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="text-dark close" data-dismiss="alert">x</button>
            <strong class="text-center text-dark">{{ $message }}</strong>
        </div>
        @endif
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="text-white close" data-dismiss="alert">x</button>
            <strong class="text-center text-dark">{{ $message }}</strong>
        </div>
        @endif
        <div class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Nombre</span>
            </div>
            <input value="{{ $paciente->nombre }}" name="nombre" class="form-control @error('nombre') is-invalid @enderror" autocomplete="off">
            @error('nombre')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror  
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Apellidos</span>
            </div>
            <input value="{{ $paciente->apellidos }}" name="apellidos" class="form-control @error('apellidos') is-invalid @enderror" autocomplete="off">
            @error('apellidos')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror  
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Sexo</span>
            </div>
            <select name="sexo" class="form-control">
                <option {{ $paciente->sexo == 'Masculino' ? 'selected' : '' }} >Masculino</option>
                <option {{ $paciente->sexo == 'Femenino' ? 'selected' : '' }} >Femenino</option>
            </select> 
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Nacimiento</span>
            </div>
            <input value="{{ $paciente->nacimiento }}" type="date" name="nacimiento" class="form-control @error('nacimiento') is-invalid @enderror" autocomplete="off">
            @error('nacimiento')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror  
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Raza</span>
            </div>
            <select name="raza" class="form-control">
                <option {{ $paciente->raza == 'Caucásico' ? 'selected' : '' }} >Caucásico</option>
                <option {{ $paciente->raza == 'Asiático' ? 'selected' : '' }} >Asiático</option>
                <option {{ $paciente->raza == 'Africano' ? 'selected' : '' }} >Africano</option>
                <option {{ $paciente->raza == 'Latino' ? 'selected' : '' }} >Latino</option>
            </select> 
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Profesión</span>
            </div>
            <input value="{{ $paciente->profesion }}" name="profesion" class="form-control" autocomplete="off">
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Fumador</span>
            </div>
            <select name="fumador" class="form-control">
                <option {{ $paciente->fumador === 0 ? 'selected' : '' }} value="0">No</option>
                <option {{ $paciente->fumador === 1 ? 'selected' : '' }} value="1">Si</option>
                <option {{ $paciente->fumador === null ? 'selected' : '' }} >Desconocido</option>
            </select>
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Bebedor</span>
            </div>
            <select name="bebedor" class="form-control">
                <option {{ $paciente->bebedor === 0 ? 'selected' : '' }} value="0">No</option>
                <option {{ $paciente->bebedor === 1 ? 'selected' : '' }} value="1">Si</option>
                <option {{ $paciente->bebedor === null ? 'selected' : '' }} >Desconocido</option>
            </select> 
        </div>      
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Carcinógenos</span>
            </div>
            <select name="carcinogenos" class="form-control">
                <option {{ $paciente->carcinogenos === 0 ? 'selected' : '' }} value="0">No</option>
                <option {{ $paciente->carcinogenos === 1 ? 'selected' : '' }} value="1">Si</option>
                <option {{ $paciente->carcinogenos === null ? 'selected' : '' }} >Desconocido</option>
            </select>
        </div>          
        <div class="d-flex justify-content-center">
            <button id="boton_crearpaciente" type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </div>
</form>
@endsection
