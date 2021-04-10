@extends('layouts.app')

@section('content')
<form method="POST">
    @CSRF
    <div class="col-md-7 pl-0">
        <h1 class="text-white text-center panel-title">Añadir paciente</h1>
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
                <span class="input-group-text">Sexo</span>
            </div>
            <select name="sexo" class="form-control">
                <option>Masculino</option>
                <option>Femenino</option>
            </select>
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Nacimiento</span>
            </div>
            <input name="nacimiento" type="date" class="form-control">
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Raza</span>
            </div>
            <select name="raza" class="form-control">
                <option>Caucásico</option>
                <option>Asiático</option>
                <option>Africano</option>
                <option>Latino</option>
            </select>
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Profesión</span>
            </div>
            <input name="profesion" class="form-control" autocomplete="off">
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Fumador</span>
            </div>
            <select name="fumador" class="form-control">
                <option value="true">Si</option>
                <option value="false">No</option>
            </select>
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Bebedor</span>
            </div>
            <select name="bebedor" class="form-control">
                <option value="true">Si</option>
                <option value="false">No</option>
            </select>
        </div>
        <div  class="my-4 input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Carcinógenos</span>
            </div>
            <select name="carcinogenos" class="form-control">
                <option value="true">Si</option>
                <option value="false">No</option>
            </select>
        </div>
        <div class="d-flex justify-content-center">
            <button id="boton_crearpaciente" type="submit" class="btn btn-primary">Confirmar</button>
        </div>
    </div>
</form>
@endsection
