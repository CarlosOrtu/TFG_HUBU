<div class="col-md-3 pl-0 mt-4">
  <ul class="navbar-nav ml-auto justify-content-end pr-5">
    <div class="sidebar-hubu border rounded p-3">
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton1" data-bs-toggle="collapse" data-bs-target="#menu1" aria-expanded="false">
          Paciente
        </button>
        <div class="collapse" id="menu1">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <li><a href="{{ route('datospaciente', $paciente->id_paciente) }}" class="text-white rounded">Datos personales</a></li>
          </ul>
        </div>
      </li>
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton2" data-bs-toggle="collapse" data-bs-target="#menu2" aria-expanded="false">
          Enfermedad
        </button>
        <div class="collapse" id="menu2">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <li><a href="{{ route('datosenfermedad', $paciente->id_paciente) }}" id="datosgenerales" class="text-white rounded">Datos generales</a></li>
            @if(isset($paciente->Enfermedad))
            <li><a href="{{ route('datossintomas', $paciente->id_paciente) }}" id="sintomas" class="text-white rounded">Sintomas</a></li>
            <li><a href="{{ route('metastasis', $paciente->id_paciente) }}" id="metastasis" class="text-white rounded">Metastasis</a></li>
            <li><a href="{{ route('biomarcadores', $paciente->id_paciente) }}" class="text-white rounded">Biomarcadores</a></li>
            <li><a href="{{ route('pruebas', $paciente->id_paciente) }}" id="pruebas" class="text-white rounded">Pruebas realizadas</a></li>
            <li><a href="{{ route('tecnicas', $paciente->id_paciente)}}" id="tecnicas" class="text-white rounded">Tecnicas realizadas</a></li>
            <li><a href="{{ route('otrostumores', $paciente->id_paciente) }}" id ="otrostumores" class="text-white rounded">Otros tumores</a></li>
            @endif
          </ul>
        </div>
      </li>
      @if(isset($paciente->Enfermedad))
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton3" data-bs-toggle="collapse" data-bs-target="#menu3" aria-expanded="false">
          Antecedentes
        </button>
        <div class="collapse" id="menu3">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <li><a href="{{ route('antecedentesmedicos', $paciente->id_paciente) }}" id="antecedentes_medicos" class="rounded text-white">Antecedentes médicos</a></li>
            <li><a href="{{ route('antecedentesoncologicos', $paciente->id_paciente) }}" id="antecedentes_oncologicos" class="text-white rounded">Antecedentes oncológicos</a></li>
            <li><a href="{{ route('antecedentesfamiliares', $paciente->id_paciente) }}" id="antecedentes_familiares" class="text-white rounded">Antecedentes familiares</a></li>
          </ul>
        </div>
      </li>
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton4" data-bs-toggle="collapse" data-bs-target="#menu4" aria-expanded="false">
          Tratamientos
        </button>
        <div class="collapse" id="menu4">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <li><a href="{{ route('quimioterapias', $paciente->id_paciente) }}" id="quimioterapia" class="rounded text-white">Quimioterapia</a></li>
            <li><a href="{{ route('radioterapias', $paciente->id_paciente) }}" id="radioterapia" class="text-white rounded">Radioterapia</a></li>
            <li><a href="{{ route('cirugias', $paciente->id_paciente) }}" id="cirugia" class="text-white rounded">Cirugía</a></li>
            <li><a href="#" class="text-white rounded">Secuencia tratamientos</a></li>
          </ul>
        </div>
      </li>
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton5" data-bs-toggle="collapse" data-bs-target="#menu5" aria-expanded="false">
          Reevaluaciones
        </button>
        <div class="collapse" id="menu5">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <?php
                $i = 1;
            ?>
            @foreach ($paciente->Reevaluaciones as $reevaluacion)
            <li><a href="{{ route('vermodificarreevaluacion', ['id' => $paciente->id_paciente, 'num_reevaluacion' => $i]) }}" class="rounded text-white">Reevaluación {{ $i }}</a></li>
            <?php
                $i = $i + 1;
            ?>
            @endforeach
            <li><a href="{{ route('reevaluacionesnuevas', $paciente->id_paciente) }}" class="rounded text-white">Nueva reevaluación</a></li>
          </ul>
        </div>
      </li>
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton6" data-bs-toggle="collapse" data-bs-target="#menu6" aria-expanded="false">
          Seguimientos
        </button>
        <div class="collapse" id="menu6">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <?php
                $i = 1;
            ?>
            @foreach ($paciente->Seguimientos as $seguimiento)
            <li><a href="{{ route('vermodificarseguimiento', ['id' => $paciente->id_paciente, 'num_seguimiento' => $i]) }}" class="rounded text-white">Seguimiento {{ $i }}</a></li>
            <?php
                $i = $i + 1;
            ?>
            @endforeach
            <li><a href="{{ route('seguimientosnuevos', $paciente->id_paciente) }}" class="rounded text-white">Nuevo seguimiento</a></li>
          </ul>
        </div>
      </li>
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton7" data-bs-toggle="collapse" data-bs-target="#menu7" aria-expanded="false">
          Comentarios
        </button>
        <div class="collapse" id="menu7">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <?php
                $i = 1;
            ?>
            @foreach ($paciente->Comentarios as $comentario)
            <li><a href="{{ route('vermodificarcomentario', ['id' => $paciente->id_paciente, 'num_comentario' => $i]) }}" class="rounded text-white">Comentario {{ $i }}</a></li>
            <?php
                $i = $i + 1;
            ?>
            @endforeach
            <li><a href="{{ route('comentarionuevo', $paciente->id_paciente) }}" class="rounded text-white">Nuevo comentario</a></li>
          </ul>
        </div>
      </li>
      @endif
    </div>
  </ul>
</div>