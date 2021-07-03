@extends('layouts.app')

@section('content')
<h1 class="text-white text-center panel-title">Filtrador de pacientes</h1>
@if ($message = Session::get('errorFiltro'))
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
@if ($message = Session::get('filtroVacio'))
<div class="alert alert-danger alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif

@if(Request::routeIs('verfiltrar'))
<form action="{{ route('realizarfiltrado') }}" method="post">
@elseif(Request::routeIs('verfiltrarpercentiles'))
<form action="{{ route('filtrarpercentiles') }}" method="post">
@elseif(Request::routeIs('verfiltrargraficas'))
<form action="{{ route('filtrargraficas') }}" method="post">
@elseif(Request::routeIs('verfiltrarkaplan'))
<form action="{{ route('filtrarkaplan') }}" method="post">
@endif
  @CSRF
  <div class="d-flex justify-content-around">
    <div>
      <h6 class="align-self-end text-white mb-4">Biomarcadores</h6>
      <div class="form-check mb-4">
        <input class="form-check-input" name="NGS" type="checkbox" id="NGS">
        <label class="text-white form-check-label" for="NGS">
          NGS
        </label>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" name="PDL1" type="checkbox" id="PDL1">
        <label class="text-white form-check-label" for="PDL1">
          PDL1
        </label>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" name="EGFR" type="checkbox" id="EGFR">
        <label class="text-white form-check-label" for="EGFR">
          EGFR
        </label>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" name="ALK" type="checkbox" id="ALK">
        <label class="text-white form-check-label" for="ALK">
          ALK
        </label>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" name="ROS1" type="checkbox" id="ROS1">
        <label class="text-white form-check-label" for="ROS1">
          ROS1
        </label>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" name="KRAS" type="checkbox" id="KRAS">
        <label class="text-white form-check-label" for="KRAS">
          KRAS
        </label>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" name="BRAF" type="checkbox" id="BRAF">
        <label class="text-white form-check-label" for="BRAF">
          BRAF
        </label>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" name="HER2" type="checkbox" id="HER2">
        <label class="text-white form-check-label" for="HER2">
          HER2
        </label>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" name="NTRK" type="checkbox" id="NTRK">
        <label class="text-white form-check-label" for="NTRK">
          NTRK
        </label>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" name="FGFR1" type="checkbox" id="FGFR1">
        <label class="text-white form-check-label" for="FGFR1">
          FGFR1
        </label>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" name="RET" type="checkbox" id="RET">
        <label class="text-white form-check-label" for="RET">
          RET
        </label>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" name="MET" type="checkbox" id="MET">
        <label class="text-white form-check-label" for="MET">
          MET
        </label>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" name="Pl3K" type="checkbox" id="Pl3K">
        <label class="text-white form-check-label" for="Pl3K">
          Pl3K
        </label>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" name="TMB" type="checkbox" id="TMB">
        <label class="text-white form-check-label" for="TMB">
          TMB
        </label>
      </div>
    </div>
    <div>
      <h6 class="align-self-end text-white mb-4">Tratamientos</h6>
      <div class="form-check mb-4">
        <input class="form-check-input" name="Radioterapia" type="checkbox" id="radioterapia">
        <label class="text-white form-check-label" for="radioterapia">
          Radioterapia
        </label>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" name="Quimioterapia" type="checkbox" id="quimioterapia">
        <label class="text-white form-check-label" for="quimioterapia">
          Quimioterapia
        </label>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" name="Cirugia" type="checkbox" id="cirugia">
        <label class="text-white form-check-label" for="cirugia">
          Cirugía
        </label>
      </div>
    </div>
  </div>
  <div class="row mt-5 d-flex justify-content-center align-items-center">
    <button class="btn btn-primary" type="submit">Realizar filtrador</button>
  </div>
</form>  
@endsection