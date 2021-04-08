@extends('layouts.app')

@section('content')

@if (count($pacientes) > 0)
<div class="panel panel-default">
    <div class="panel-heading">
		<h1 class="text-white text-center panel-title">Listado de pacientes</h1>
    <div class=panel-body>
    	<div class="table-responsive">
	        <table id="tablaPacientes" class="table table-dark table-bordered">
	            <thead>
	            	<tr>
	                	<th>ID Paciente</th>
	                    <th>Nombre</th>
	                    <th>Apellidos</th>
						<th>Seleccionar</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
	        			<td>
	        				<input placeholder="ID" class="form-control mb-2 mr-2" type="text" >
	        			</td>
	        			<td>
	        				<input placeholder="Nombre" class="form-control mb-2 mr-2" type="text" >
	        			</td>	                            			
	        			<td>
	        				<input placeholder="Apellidos" class="form-control mb-2 mr-2" type="text" >
	        			</td>
	        			<td>
	        			</td>
	        		</tr>
	            </tfoot>

	            <tbody>
	                @foreach ($pacientes as $paciente)
	                    <tr>
	                    	<td class="table-text text-dark"><div>{{ $paciente->id_paciente }}</div></td>
	                        <td class="table-text text-dark"><div>{{ $paciente->nombre }}</div></td>
	                        <td class="table-text text-dark"><div>{{ $paciente->apellidos }}</div></td>
							<td>
								<a href=""} ><input type="button" class="btn btn-primary" value="Ver"/></a>
								<a href=""} ><input type="button" class="btn btn-warning" value="Editar"/></a>
							</td>
	                    </tr>
	                @endforeach
	            </tbody>
	        </table>
	    </div>
    </div>
</div>
@else
<h1 class="text-white panel-title">No hay pacientes</h1>
@endif
    
<script type="text/javascript">
$(document).ready(function() {
    var table = $('#tablaPacientes').DataTable({
        initComplete: function () {
            //Hacemos que cada buscador del tfoot busque en una columna
            this.api().columns().every( function () {
                var that = this;
 
                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
        },        
        //Cambiamos el lenguaje a español
        "language": {
            "lengthMenu": "Mostrar _MENU_ filas por página",
            "zeroRecords": "Nothing found - sorry",
            "info": "Página _PAGE_ de _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total records)",
			"url": "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"

        }
    });
 
} );
</script>
@endsection
