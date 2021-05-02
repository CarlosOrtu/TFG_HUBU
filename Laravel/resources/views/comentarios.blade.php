@extends('layouts.app')
 
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h6 class="align-self-end text-white">Paciente: {{ $nombre }}</h6>
    <h1 class="align-self-center text-white panel-title">Comentario {{ $posicion+1 }}</h1>
    <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@error('comentario')
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
<form action="{{ route('modificarcomentario', ['id' => $paciente->id_paciente, 'num_comentario' => $posicion]) }}" method="post">
    @CSRF
    @method('put')
    <div class="my-4 form-group">
      <label for="comentario" class="text-white" >Comentario</label>
      <textarea id="comentario" rows="10" name="comentario"  class="form-control" autocomplete="off">{{ $comentario->comentario}}</textarea>
    </div>
    <div class="d-flex justify-content-center mb-4">
        <button type="submit" class="btn btn-primary">Modificar</button>
</form>
      <form action="{{ route('eliminarcomentario', ['id' => $paciente->id_paciente, 'num_comentario' => $posicion]) }}" method="post">
        @CSRF
        @method('delete')
        <button class="ml-2 btn btn-warning">Eliminar</button>
      </form>
    </div>
<script src="{{ asset('/js/nuevocampo.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/especificar_otro.js') }}" type="text/javascript"></script>
@endsection

