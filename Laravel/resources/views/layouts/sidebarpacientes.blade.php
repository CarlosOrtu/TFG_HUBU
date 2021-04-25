<div class="col-md-3 pl-0 mt-4">
  <div class="sidebar-hubu border rounded p-3">
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
</div>

