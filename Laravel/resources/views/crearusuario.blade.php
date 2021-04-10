@extends('layouts.app')

@section('content')
<form method="POST">
    @CSRF
    <div class="col-md-7 pl-0">
        <h1 class="text-white text-center panel-title">Añadir usuario</h1>
        <div class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Nombre</span>
            </div>
            <input name="nombre" class="form-control" autocomplete="off">
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Apellidos</span>
            </div>
            <input name="apellidos" class="form-control" autocomplete="off">
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Correo</span>
            </div>
            <input name="correo" type="email" class="form-control" autocomplete="off">
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Contraseña</span>
            </div>
            <input name="contrasena" type="password" class="form-control" >
            @error('pass')
            <span class="invalid-feedback" role="alert">
                <strong>Los datos son incorrectos</strong>
            </span>
            @enderror
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Repetir <br>contraseña</span>
            </div>
            <input name="contrasena_repetir" type="password" class="form-control">
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Rol</span>
            </div>
            <select name="rol" class="form-control">
                <option value="1">Admistrador</option>
                <option value="2">Oncólogo</option>
            </select>
        </div>
        <div class="d-flex justify-content-center">
            <button id="boton_crearpaciente" type="submit" class="btn btn-primary">Confirmar</button>
        </div>
    </div>
</form>
@endsection
