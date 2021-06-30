@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-around">
  <div>
    <h4 class="text-white align-self-center text-center">Gráfica kaplan meier general</h4>
    <img src="{{ URL::to('/kaplanmeierGeneral.png') }}">
  </div>
  <div>
    <h4 class="text-white align-self-center text-center">Gráfica kaplan meier dividida por {{ $division }}</h4>
    <img src="{{ URL::to('/kaplanmeierDivisiones.png') }}">
  </div>
</div>
<div class="row mt-5 d-flex justify-content-around align-items-center">
  <a class="btn btn-info" href="{{ URL::to('/kaplanmeierGeneral.png') }}" download="KaplanMeierGeneral"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 bi bi-download" viewBox="0 0 16 16">
  <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
  <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
</svg>Descargar gráfica</a>
  <a href="{{ URL::to('/kaplanmeierDivisiones.png') }}" download="KaplanMeierDivision" class="text-white btn btn-info"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 bi bi-download" viewBox="0 0 16 16">
  <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
  <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
</svg></span>Descargar gráfica</a>
</div>
<div class="row mt-5 d-flex justify-content-center align-items-center">
  <a href="{{ route('verkaplan') }}" ><input type="button" class="px-5 btn btn-info" value="Nueva gráfica"/></a>
</div>
@endsection