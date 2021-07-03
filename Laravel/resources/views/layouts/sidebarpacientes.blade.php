<div class="col-md-3 pl-0 mt-5">
  <div class="sidebar-hubu border rounded p-3 mb-4">
    <ul class="navbar-nav ml-auto justify-content-end pr-5">
      <li class="mb-1">
        <button class="btn btn-toggle text-white" onClick="location.href='{{ route('pacientes') }}'">Ver pacientes</button>
      </li>
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white" onClick="location.href='{{ route('nuevopaciente') }}'">Añadir nuevo paciente</button>
      </li>  
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white" onClick="location.href='{{ route('vereliminarpaciente') }}'">Eliminar paciente</button>
      </li>  
    </ul>
  </div>
  <a href="{{ route('verfiltrar') }}" class="align-self-center mt-5"><button type="button" class="btn btn-block btn-primary">Aplicar filtro</button></a>
</div>

