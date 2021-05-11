@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-4">
    @env('production')
    <h6 class="align-self-end text-white">Paciente: {{ $nombre }}</h6>
    @endenv
    @env('local')
    <h6 class="align-self-end text-white">Paciente: {{ $paciente->nombre }}</h6>
    @endenv
    <h1 class="align-self-center text-white panel-title">Secuencia tratamientos</h1>
    <h6 class="align-self-end text-white">Ultima modificación: {{ $paciente->ultima_modificacion }}</h6>
</div>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['timeline'], 'language': 'es'});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var container = document.getElementById('timeline');
        var chart = new google.visualization.Timeline(container);
        var dataTable = new google.visualization.DataTable();

        dataTable.addColumn({ type: 'string', id: 'Tratamiento' });
        dataTable.addColumn({ type: 'date', id: 'Comienzo' });
        dataTable.addColumn({ type: 'date', id: 'Fin' });
        dataTable.addRows([
          @foreach($quimioterapias as $quimioterapia)
          	['Quimioterapia', new Date({{ substr($quimioterapia->fecha_inicio,0,4) }},{{ substr($quimioterapia->fecha_inicio,5,2) }},{{ substr($quimioterapia->fecha_inicio,8,2) }}), new Date({{ substr($quimioterapia->fecha_fin,0,4) }},{{ substr($quimioterapia->fecha_fin,5,2) }},{{ substr($quimioterapia->fecha_fin,8,2) }})],
          @endforeach
          @foreach($radioterapias as $radioterapia)
          	['Radioterapia', new Date({{ substr($radioterapia->fecha_inicio,0,4) }},{{ substr($radioterapia->fecha_inicio,5,2) }},{{ substr($radioterapia->fecha_inicio,8,2) }}), new Date({{ substr($radioterapia->fecha_fin,0,4) }},{{ substr($radioterapia->fecha_fin,5,2) }},{{ substr($radioterapia->fecha_fin,8,2) }})],
          @endforeach
          @foreach($cirugias as $cirugia)
          	['Cirugia',new Date({{ substr($cirugia->fecha_inicio,0,4) }},{{ substr($cirugia->fecha_inicio,5,2) }},{{ substr($cirugia->fecha_inicio,8,2) }}), new Date({{ substr($cirugia->fecha_inicio,0,4) }},{{ substr($cirugia->fecha_inicio,5,2) }},{{ substr($cirugia->fecha_inicio,8,2) }})],
          @endforeach         
          ]);

	    var options = {
	      timeline: { colorByRowLabel: true }
	    };
		  google.visualization.events.addListener(chart, 'ready', function () {
		    var labels = container.getElementsByTagName('text');
		    Array.prototype.forEach.call(labels, function(label) {
			      if (label.getAttribute('text-anchor') === 'middle') {
		        label.setAttribute('fill', '#ffffff');
		      }
		    });
		  });

        chart.draw(dataTable);
      }
    </script>
<div id="timeline" style="height: 180px;"></div>
@endsection