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
    var tipos = [];
    var numTipos = [];
    <?php $tipos = array_keys($pacientes->groupBy('profesion')->toArray()) ?>
    @foreach($tipos as $tipo)
      tipos.push({
        key: '{{ $tipo }}',
        value: {{ $pacientes->where('profesion',$tipo)->count() }}
      });
    @endforeach
    console.log(tipos[0].key);
    data.addColumn('string', 'Sexo');
    data.addColumn('number', 'Número');
    data.addRows([
      @for ($i = 0; $i < count($tipos) ; $i++)
        [tipos[{{ $i }}].key,tipos[{{ $i }}].value],
      @endfor
    ]);

    // Set chart options
    var options = {'title':'How Much Pizza I Ate Last Night',
                   'width':1000,
                   'height':800};

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);
  }
</script>
<input >
<div id="chart_div"></div>
@endsection