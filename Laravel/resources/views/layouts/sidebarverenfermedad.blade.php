<div class="col-md-3 pl-0 mt-4">
  <ul class="navbar-nav ml-auto justify-content-end pr-5">
    <div class="sidebar-hubu border rounded p-3">
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton1" data-toggle="collapse" data-target="#menu1" aria-expanded="false">
          Paciente
        </button>
        <div class="collapse" id="menu1">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <li><a href="{{ route('verdatospaciente', $paciente->id_paciente) }}" class="text-white rounded">Datos personales</a></li>
          </ul>
        </div>
      </li>
      @if(isset($paciente->Enfermedad))
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton2" data-toggle="collapse" data-target="#menu2" aria-expanded="false">
          Enfermedad
        </button>
        <div class="collapse" id="menu2">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <li><a href="{{ route('verdatosenfermedad', $paciente->id_paciente) }}" id="datosgenerales" class="text-white rounded">Datos generales</a></li>
            <li><a href="{{ route('verdatossintomas', $paciente->id_paciente) }}" id="sintomas" class="text-white rounded">Sintomas</a></li>
            <li><a href="{{ route('vermetastasis', $paciente->id_paciente) }}" id="metastasis" class="text-white rounded">Metastasis</a></li>
            <li><a href="{{ route('verbiomarcadores', $paciente->id_paciente) }}" class="text-white rounded">Biomarcadores</a></li>
            <li><a href="{{ route('verpruebas', $paciente->id_paciente) }}" id="pruebas" class="text-white rounded">Pruebas realizadas</a></li>
            <li><a href="{{ route('vertecnicas', $paciente->id_paciente)}}" id="tecnicas" class="text-white rounded">Tecnicas realizadas</a></li>
            <li><a href="{{ route('verotrostumores', $paciente->id_paciente) }}" id ="otrostumores" class="text-white rounded">Otros tumores</a></li>
          </ul>
        </div>
      </li>
      @endif
      @if(isset($paciente->Enfermedad))
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton3" data-toggle="collapse" data-target="#menu3" aria-expanded="false">
          Antecedentes
        </button>
        <div class="collapse" id="menu3">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <li><a href="{{ route('verantecedentesmedicos', $paciente->id_paciente) }}" id="antecedentes_medicos" class="rounded text-white">Antecedentes médicos</a></li>
            <li><a href="{{ route('verantecedentesoncologicos', $paciente->id_paciente) }}" id="antecedentes_oncologicos" class="text-white rounded">Antecedentes oncológicos</a></li>
            <li><a href="{{ route('verantecedentesfamiliares', $paciente->id_paciente) }}" id="antecedentes_familiares" class="text-white rounded">Antecedentes familiares</a></li>
          </ul>
        </div>
      </li>
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton4" data-toggle="collapse" data-target="#menu4" aria-expanded="false">
          Tratamientos
        </button>
        <div class="collapse" id="menu4">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <li><a href="{{ route('verquimioterapias', $paciente->id_paciente) }}" id="quimioterapia" class="rounded text-white">Quimioterapia</a></li>
            <li><a href="{{ route('verradioterapias', $paciente->id_paciente) }}" id="radioterapia" class="text-white rounded">Radioterapia</a></li>
            <li><a href="{{ route('vercirugias', $paciente->id_paciente) }}" id="cirugia" class="text-white rounded">Cirugía</a></li>
            <li><a href="#" class="text-white rounded">Secuencia tratamientos</a></li>
          </ul>
        </div>
      </li>
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton5" data-toggle="collapse" data-target="#menu5" aria-expanded="false">
          Reevaluaciones
        </button>
        <div class="collapse" id="menu5">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <li><a href="{{ route('verreevaluaciones', $paciente->id_paciente) }}" class="rounded text-white">Reevaluaciones</a></li>
          </ul>
        </div>
      </li>
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton6" data-toggle="collapse" data-target="#menu6" aria-expanded="false">
          Seguimientos
        </button>
        <div class="collapse" id="menu6">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <li><a href="{{ route('verseguimientos', $paciente->id_paciente) }}" class="rounded text-white">Seguimientos</a></li>
          </ul>
        </div>
      </li>
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white desplegable" id="boton7" data-toggle="collapse" data-target="#menu7" aria-expanded="false">
          Comentarios
        </button>
        <div class="collapse" id="menu7">
          <ul class="btn-toggle-nav list-unstyled ml-5 pb-1 small">
            <li><a href="{{ route('vercomentarios', $paciente->id_paciente) }}" class="rounded text-white">Comentarios</a></li>
          </ul>
        </div>
      </li>
      @endif
    </div>
  </ul>
</div>