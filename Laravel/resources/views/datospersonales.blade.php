@extends('layouts.app')

@section('content')
<form action="{{ route('datospersonales') }}" method="post">
    @CSRF
    @method('put')
    <div class="col-md-7 pl-0">
        <h1 class="text-white text-center panel-title">Modificar datos personales</h1>
        <div class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Nombre</span>
            </div>
            <input value="{{ Auth::user()->nombre }}" name="nombre" class="form-control @error('nombre') is-invalid @enderror" autocomplete="off">
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
            <input value="{{ Auth::user()->apellidos }}" name="apellidos" class="form-control @error('apellidos') is-invalid @enderror" autocomplete="off">
            @error('apellidos')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror  
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Correo</span>
            </div>
            <input value="{{ Auth::user()->email }}" name="correo" class="form-control @error('correo') is-invalid @enderror" autocomplete="off">
            @error('correo')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror  
        </div>
        <div class="d-flex justify-content-center">
            <button id="boton_crearpaciente" type="submit" class="btn btn-primary">Confirmar</button>
        </div>
    </div>
</form>
@endsection
