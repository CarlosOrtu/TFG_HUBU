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

    if(tipoGrafica == 'circular')
      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    else
      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
   
  $("#descargar").click(function() {
    google.visualization.events.addListener(chart, 'ready', function () {
      chart_div.innerHTML = '<img id="chart" src=' + chart.getImageURI() + '>';
      document.getElementById("download_link").setAttribute("href", chart.getImageURI())
    });
  });

    chart.draw(data, options);
  }
</script>
<?php 
  $acumulado = 0;
?>
<div class="d-flex justify-content-around">
  <div class="d-flex justify-content-center mr-4" id="chart_div"></div>
  <table class="text-white table table-bordered">
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

          $percentil = ($datosGrafica[$clave]/$total)*100;
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
            $totalPercentil = $totalPercentil + ($datosGrafica[$clave]/$total)*100;
          }

        ?>    
        <td>{{ $totalFrecuencia }}</td>
        <td>{{ $totalPercentil }}</td>
        <td></td>
      </tr>  
    </tbody>
  </table>  
</div>
<div class="mt-5 d-flex justify-content-around align-items-center">
  <button id="descargar" class="btn btn-info">Descargar gráfica</button>
  <a href="{{ route('vergraficas') }}" ><input type="button" class="btn btn-info" value="Nueva gráfica"/></a>
</div>
@endsection