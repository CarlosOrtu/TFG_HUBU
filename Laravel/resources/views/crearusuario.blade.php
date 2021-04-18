    @extends('layouts.app')

@section('content')
<form method="POST">
    @CSRF
    <div class="col-md-12 pl-0">
        <h1 class="text-white text-center panel-title">Añadir usuario</h1>
        <div class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Nombre</span>
            </div>
            <input name="nombre" value="{{ old('nombre') }}" class="form-control @error('nombre') is-invalid @enderror" autocomplete="off">
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
            <input name="apellidos" value="{{ old('apellidos') }}" class="form-control @error('apellidos') is-invalid @enderror" autocomplete="off">
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
            <input name="correo" value="{{ old('correo') }}" class="form-control @error('correo') is-invalid @enderror" autocomplete="off">
            @error('correo')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Contraseña</span>
            </div>
            <input name="contrasena" type="password" class="form-control @error('contrasena') is-invalid @enderror" >
            @error('contrasena')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Repetir <br>contraseña</span>
            </div>
            <input name="contrasena_repetir" type="password" class="form-control @error('contrasena_repetir') is-invalid @enderror">
            @error('contrasena_repetir')
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
                <option {{ old('rol') == 1 ? 'selected' : '' }} value="1">Admistrador</option>
                <option {{ old('rol') == 2 ? 'selected' : '' }} value="2">Oncólogo</option>
            </select>
        </div>
        <div class="d-flex justify-content-center">
            <button id="boton_crearpaciente" type="submit" class="btn btn-primary">Confirmar</button>
        </div>
    </div>
</form>
@endsection
