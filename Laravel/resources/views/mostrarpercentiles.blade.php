@extends('layouts.app')

@section('content')
<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

    var data = google.visualization.arrayToDataTable([
      [ '{{ $tipoDato }}', {{ $percentiles[0] }}, {{ $percentiles[3] }}, {{ $percentiles[5] }}, {{ $percentiles[8] }} ]
      // Treat first row as data as well.
    ], true);

  var options = {'title':'Grafica percentiles',
                   'width':600,
                   'height':450};

    var chart = new google.visualization.CandlestickChart(document.getElementById('chart_div'));

    google.visualization.events.addListener(chart, 'ready', function () {
      document.getElementById("descargar").setAttribute("href", chart.getImageURI())
    });

    chart.draw(data, options);
  }
</script>
<div class="d-flex justify-content-around">
  <div class="d-flex justify-content-center mr-4" id="chart_div"></div>
  <table id="tabla_grafica" class="text-white table table-bordered">
    <caption>Tabla de percentiles</caption>
    <thead>
      <tr>
        <th scope="col"></th>
        <th scope="col">Percentiles</th>
        <th scope="col">Smallest</th>
        <th scope="col"></th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">1%</th>
        <td>{{ $percentiles[0] }}</td>  
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <th scope="row">5%</th>   
        <td>{{ $percentiles[1] }}</td>
        <td></td>
        <td></td>
        <td></td>
      </tr>  
      <tr>
        <th scope="row">10%</th>   
        <td>{{ $percentiles[2] }}</td>
        <td></td>
        <td>Obs</td>
        <td>{{ count($datosGraficaPercentil) }}</td>
      </tr>  
      <tr>
        <th scope="row">25%</th>   
        <td>{{ $percentiles[3] }}</td>
        <td></td>
        <td></td>
        <td></td>
      </tr>  
      <tr>
        <th scope="row"></th>   
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <th scope="row">50%</th>   
        <td>{{ $percentiles[4] }}</td>
        <td></td>
        <td>Mean</td>
        <td>{{ $media }}</td>
      </tr>  
        <th scope="row"></th>   
        <td></td>
        <td>Largest</td>
        <td>Std. Dev.s</td>
        <td>{{ $desviacion }}</td>
      <tr>
      </tr>
      <tr>
        <th scope="row">75%</th>   
        <td>{{ $percentiles[5] }}</td>
        <td></td>
        <td></td>
        <td></td>
      </tr>  
      <tr>
        <th scope="row">90%</th>   
        <td>{{ $percentiles[6] }}</td>
        <td></td>
        <td>Variance</td>
        <td>{{ pow($desviacion, 2)}}</td>
      </tr>  
      <tr>
        <th scope="row">95%</th>   
        <td>{{ $percentiles[7] }}</td>
        <td></td>
        <td>Skewness</td>
        <td>{{ $skewness }}</td>
      </tr>  
      <tr>
        <th scope="row">99%</th>   
        <td>{{ $percentiles[8] }}</td>
        <td></td>
        <td>Kurtosis</td>
        <td>{{ $kurtosis }}</td>
      </tr>  
    </tbody>
  </table>  
</div>
<div class="row mt-5 d-flex justify-content-around align-items-center">
  <a id="descargar" class="btn btn-info" href="/" download="grafica"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 bi bi-download" viewBox="0 0 16 16">
  <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
  <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
</svg>Descargar gráfica</a>
  <a onclick="exportarExcel(this)" href="" class="text-white btn btn-info"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 bi bi-download" viewBox="0 0 16 16">
  <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
  <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
</svg></span>Descargar tabla</a>
</div>
<div class="row mt-5 d-flex justify-content-center align-items-center">
  <a href="{{ route('verpercentiles') }}" ><input type="button" class="px-5 btn btn-info" value="Nuevo percentil"/></a>
</div>
<script type="text/javascript">
function exportarExcel(elem) {
  var table = document.getElementById("tabla_grafica");
  var html = table.outerHTML;
  var url = 'data:application/vnd.ms-excel,' + escape(html); // Set your html table into url 
  elem.setAttribute("href", url);
  elem.setAttribute("download", "TablaGrafica.xls"); // Choose the file name
  return false;
}
</script>
@endsection