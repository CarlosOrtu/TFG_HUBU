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
    data.addColumn('string', '{{ $tipo }}');
    data.addColumn('number', 'Número');
    data.addRows([
      @foreach(array_keys($datosGrafica) as $clave)
        ['{{ $clave }}',{{ $datosGrafica[$clave] }}],
      @endforeach
    ]);

    // Set chart options
    var options = {'title':'Grafica dividida por {{ $tipo }}',
                   'width':600,
                   'height':450};

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);
  }
</script>
<div class="d-flex justify-content-center" id="chart_div"></div>
@endsection