<div class="col-md-3 pl-0 mt-5">
  <div class="sidebar-hubu border rounded p-3 mb-4">
    <ul class="navbar-nav ml-auto justify-content-end pr-5">
      <li class="mb-1">
        <button class="btn btn-toggle text-white" onClick="location.href='{{ route('vergraficas') }}'">Gráficas generales</button>
      </li>
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white" onClick="location.href='{{ route('verpercentiles') }}'">Percentiles</button>
      </li>  
      <div class="dropdown-divider"></div>
      <li class="mb-1">
        <button class="btn btn-toggle text-white" onClick="location.href='{{ route('verkaplan') }}'">Kaplan meier</button>
      </li>  
    </ul>
  </div>
  @if(Request::routeIs('verpercentiles') or Request::routeIs('filtrarpercentiles'))
  <a href="{{ route('verfiltrarpercentiles') }}" class="align-self-center mt-5"><button type="button" class="btn btn-block btn-primary">Aplicar filtro</button></a>
  @elseif(Request::routeIs('vergraficas') or Request::routeIs('filtrargraficas'))
  <a href="{{ route('verfiltrargraficas') }}" class="align-self-center mt-5"><button type="button" class="btn btn-block btn-primary">Aplicar filtro</button></a>
  @elseif(Request::routeIs('verkaplan') or Request::routeIs('filtrarkaplan'))
  <a href="{{ route('verfiltrarkaplan') }}" class="align-self-center mt-5"><button type="button" class="btn btn-block btn-primary">Aplicar filtro</button></a>
  @endif
</div>

