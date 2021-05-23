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

    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Tipo');
    data.addColumn('number', 'Número');
    data.addRows([
      @foreach(array_keys($datosGrafica) as $clave)
        ['{{ $clave }}',{{ $datosGrafica[$clave] }}],
      @endforeach
    ]);

    // Set chart options
    var options = {'title':'Grafica dividida por @foreach($tipos as $tipo) @if($loop->first) {{ $tipo }} @else {{ ' y '.$tipo }} @endif @endforeach',
                   'width':600,
                   'height':450};

    // Instantiate and draw our chart, passing in some options.
    var tipoGrafica = '{{ $tipoGrafica }}';
    console.log(tipoGrafica);
    if(tipoGrafica == 'circular')
      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    else
      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
   

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
        <th scope="col">{{ $tipos[0] }}</th>
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
@endsection