@extends('layouts.app')

@section('content')
<form action="{{ route('modificarusuario', ['id' => $usuario->id_user]) }}" method="post">
    @CSRF
    @method('put')
    <div class="col-md-7 pl-0">
        <h1 class="text-white text-center panel-title">Modificar usuario</h1>
        <div class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Nombre</span>
            </div>
            <input value="{{ $usuario->name }}" name="nombre" class="form-control" autocomplete="off">
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Apellidos</span>
            </div>
            <input value="{{ $usuario->surname }}" name="apellidos" class="form-control" autocomplete="off">
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Correo</span>
            </div>
            <input value="{{ $usuario->email }}" name="correo" type="email" class="form-control" autocomplete="off">
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Rol</span>
            </div>
            <select name="rol" class="form-control">
                @if($usuario->id_role == 1)
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
