<div class="col-md-3 pl-0 mt-4">
  <ul class="navbar-nav ml-auto justify-content-end pr-5">
    <div class="border rounded p-3 bg-transparent">
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton1" data-bs-toggle="collapse" data-bs-target="#menu1" aria-expanded="false">
          Paciente
        </button>
        <div class="collapse" id="menu1">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <li><a href="{{ route('datospaciente', $paciente->id_paciente) }}" class="text-white rounded">Paciente</a></li>
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
            <li><a href="#" class="text-white rounded">Sintomas</a></li>
            <li><a href="#" class="text-white rounded">Metastasis</a></li>
            <li><a href="#" class="text-white rounded">Biomarcadores</a></li>
            <li><a href="#" class="text-white rounded">Pruebas realizadas</a></li>
            <li><a href="#" class="text-white rounded">Tecnicas realizadas</a></li>
            <li><a href="#" class="text-white rounded">Otros tumores</a></li>
          </ul>
        </div>
      </li>
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton3" data-bs-toggle="collapse" data-bs-target="#menu3" aria-expanded="false">
          Antecedentes
        </button>
        <div class="collapse" id="menu3">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <li><a href="#" class="rounded text-white">Antecedentes médicos</a></li>
            <li><a href="#" class="text-white rounded">Antecedentes oncológicos</a></li>
            <li><a href="#" class="text-white rounded">Antecedentes familiares</a></li>
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
            <li><a href="#" class="rounded text-white">Quimioterapia</a></li>
            <li><a href="#" class="text-white rounded">Radioterapia</a></li>
            <li><a href="#" class="text-white rounded">Cirguia</a></li>
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
            <li><a href="#" class="rounded text-white">Reevaluación {{ $i }}</a></li>
            <?php
                $i = $i + 1;
            ?>
            @endforeach
            <li><a href="#" class="rounded text-white">Nueva reevaluación</a></li>
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
            <li><a href="#" class="rounded text-white">Seguimiento {{ $i }}</a></li>
            <?php
                $i = $i + 1;
            ?>
            @endforeach
            <li><a href="#" class="rounded text-white">Nuevo seguimiento</a></li>
          </ul>
        </div>
      </li>
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton8" data-bs-toggle="collapse" data-bs-target="#menu8" aria-expanded="false">
          Comentario
        </button>
        <div class="collapse" id="menu8">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <?php
                $i = 1;
            ?>
            <li><a href="#" class="rounded text-white">Comentario {{ $i }}</a></li>
            <?php
                $i = $i + 1;
            ?>
            <li><a href="#" class="rounded text-white">Nuevo comentario</a></li>
          </ul>
        </div>
      </li>
    </div>
  </ul>
</div>