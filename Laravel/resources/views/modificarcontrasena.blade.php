@extends('layouts.app')

@section('content')
<form action="{{ route('modificarcontrasena') }}" method="post">
    @CSRF
    @method('put')
    <div class="col-md-12 pl-0">
        <h1 class="text-white text-center panel-title">Modificar contraseña</h1>
        <div class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Contraseña <br>antigua</span>
            </div>
            <input name="contrasena_antigua" type="password" class="form-control @error('contrasena_antigua') is-invalid @enderror" autocomplete="off">
            @error('contrasena_antigua')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror  
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Contraseña <br>nueva</span>
            </div>
            <input name="contrasena_nueva"  type="password" class="form-control @error('contrasena_nueva') is-invalid @enderror" autocomplete="off">
            @error('contrasena_nueva')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror  
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Repetir <br>contraseña</span>
            </div>
            <input name="contrasena_nueva_repetida" type="password" class="form-control @error('contrasena_nueva_repetida') is-invalid @enderror" autocomplete="off">
            @error('contrasena_nueva_repetida')
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
