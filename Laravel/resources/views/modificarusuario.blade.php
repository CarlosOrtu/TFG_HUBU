@extends('layouts.app')

@section('content')
<form action="{{ route('modificarusuario', ['id' => $usuario->id_usuario]) }}" method="post">
    @CSRF
    @method('put')
    <div class="col-md-7 pl-0">
        <h1 class="text-white text-center panel-title">Modificar usuario</h1>
        <div class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Nombre</span>
            </div>
            <input value="{{ $usuario->nombre }}" name="nombre" class="form-control @error('nombre') is-invalid @enderror" autocomplete="off">
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
            <input value="{{ $usuario->apellidos }}" name="apellidos" class="form-control @error('apellidos') is-invalid @enderror" autocomplete="off">
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
            <input value="{{ $usuario->email }}" name="correo" class="form-control @error('correo') is-invalid @enderror" autocomplete="off">
            @error('correo')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror  
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Rol</span>
            </div>
            <select name="rol" class="form-control">
                @if($usuario->id_rol == 1)
                <option value="1" selected="true">Admistrador</option>
                <option value="2">Oncólogo</option>
                @else
                <option value="1">Admistrador</option>
                <option value="2" selected="true">Oncólogo</option>
                @endif
            </select>
        </div>
        <div class="d-flex justify-content-center">
            <button id="boton_crearpaciente" type="submit" class="btn btn-primary">Confirmar</button>
        </div>
    </div>
</form>
@endsection
