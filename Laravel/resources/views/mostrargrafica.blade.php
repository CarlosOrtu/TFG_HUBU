@extends('layouts.app')

@section('content')
<script type="text/javascript">

  // Load the Visualization API and the corechart package.
  google.charts.load('current', {'packages':['corechart']});

  // Set a callback to run when the Google Visualization API is loaded.
  google.charts.setOnLoadCallback(drawChart);

  // Callback that creates and populates a data table,
  // instantiates the pie chart, passes in the data and 
  // draws it.
  function drawChart() {

    var tipoGrafica = '{{ $tipoGrafica }}';

    <?php 
      if(count($tipos) > 1){
        $valoresDif1 = array();
        $valoresDif2 = array();
        foreach(array_keys($datosGrafica) as $clave){
          $dato1 = explode(" y ", $clave)[0];
          $dato2 = explode(" y ", $clave)[1];
          if(!in_array($dato1,$valoresDif1))
            array_push($valoresDif1, $dato1);
          if(!in_array($dato2,$valoresDif2))
            array_push($valoresDif2, $dato2);
        }
      }
    ?>
    @if(count($tipos) > 1 and $tipoGrafica != 'circular')
    var data = google.visualization.arrayToDataTable([
      ['Pacientes', @foreach($valoresDif2 as $valorDif2) '{{ $valorDif2 }}', @endforeach],
      @foreach($valoresDif1 as $valorDif1)
      ['{{ $valorDif1 }}',
        @foreach($valoresDif2 as $valorDif2) 
          @if(in_array($valorDif1.' y '.$valorDif2,array_keys($datosGrafica)))
            {{ $datosGrafica[$valorDif1.' y '.$valorDif2] }}, 
          @else
            0,
          @endif
        @endforeach
        ],
      @endforeach
    ]);
    @else
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Tipo');
    data.addColumn('number', 'Pacientes');
    data.addRows([
      @foreach(array_keys($datosGrafica) as $clave)
        ['{{ $clave }}',{{ $datosGrafica[$clave] }}],
      @endforeach
    ]);
    @endif

    // Set chart options
    var options = {'title':'Grafica dividida por @foreach($tipos as $tipo) @if($loop->first) {{ $tipo }} @else {{ ' y '.$tipo }} @endif @endforeach',
                  'vAxis': { title: "Número de pacientes" },
                  'hAxis': { title: "{{ $tipos[0] }}" },
                  'legend':{ title: "prueba" },

                   'width':600,
                   'height':450};

    if(tipoGrafica == 'circular'){
      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    }else
      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

    google.visualization.events.addListener(chart, 'ready', function () {
      document.getElementById("descargar").setAttribute("href", chart.getImageURI())
    });

    chart.draw(data, options);
  
  }
</script>
<?php 
  $acumulado = 0;
?>
<div class="d-flex justify-content-around">
  <div class="d-flex justify-content-center mr-4" id="chart_div"></div>
  <table id="tabla_grafica" class="text-white table table-bordered">
    <thead>
      <tr>
        <th scope="col">@foreach($tipos as $tipo) @if($loop->first) {{ $tipo }} @else {{ ' y '.$tipo }} @endif @endforeach</th>
        <th scope="col">Frecuencia</th>
        <th scope="col">Percentil</th>
        <th scope="col">Acumulada</th>
      </tr>
    </thead>
    <tbody>
      @foreach(array_keys($datosGrafica) as $clave)
      <tr>
        <th scope="row">{{ $clave }}</th>
        <td>{{ $datosGrafica[$clave] }}</td>
        <?php 
          $total = 0;
          foreach(array_keys($datosGrafica) as $claveTotal){
            $total = $total + $datosGrafica[$claveTotal];
          }

          $percentil = round(($datosGrafica[$clave]/$total)*100,2);
        ?>    
        <td>{{ $percentil }}</td>
        <?php 
          $acumulado = $acumulado + $percentil;
        ?>
        <td>{{ $acumulado }}</td>
      </tr>
      @endforeach
      <tr>
        <th scope="row">Total</th>
        <?php 
          $totalFrecuencia = 0;
          $totalPercentil = 0;
          foreach(array_keys($datosGrafica) as $claveTotal){
            $totalFrecuencia = $totalFrecuencia + $datosGrafica[$claveTotal];
            $totalPercentil = $totalPercentil + round(($datosGrafica[$claveTotal]/$total)*100,2);
          }

        ?>    
        <td>{{ $totalFrecuencia }}</td>
        <td>{{ $totalPercentil }}</td>
        <td></td>
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
  <a href="{{ route('vergraficas') }}" ><input type="button" class="px-5 btn btn-info" value="Nueva gráfica"/></a>
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