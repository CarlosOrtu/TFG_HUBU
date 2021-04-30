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
	                	<th class="left">ID Paciente</th>
	                    <th>Nombre</th>
	                    <th>Apellidos</th>
						<th class="right">Seleccionar</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
	        			<td class="left">
	        				<input placeholder="ID" class="form-control mb-2 mr-2" type="text" autocomplete="off">
	        			</td>
	        			<td>
	        				<input placeholder="Nombre" class="form-control mb-2 mr-2" type="text" autocomplete="off">
	        			</td>	                            			
	        			<td>
	        				<input placeholder="Apellidos" class="form-control mb-2 mr-2" type="text" autocomplete="off">
	         			</td>
	        			<td class="right">
	        			</td>
	        		</tr>
	            </tfoot>

	            <tbody>
	                @foreach ($pacientes as $paciente)
	                    <tr>
	                    	<td class="table-text text-dark left"><div>{{ $paciente->id_paciente }}</div></td>
	                        <td class="table-text text-dark"><div>{{ $paciente->nombre }}</div></td>
	                        <td class="table-text text-dark"><div>{{ $paciente->apellidos }}</div></td>
							<td class="right">
								<a onClick="confirmarEliminacion(this)"><input type="button" class="btn btn-warning" value="Eliminar"/></a>
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
	function confirmarEliminacion(element) {
		var idPaciente = $(element).parent().prev().prev().prev().text();
		var urlPrincipal = $(location).attr('origin');
		var entorno = "<?php echo env('APP_ENV');?>";
		if(entorno == "local"){
			var url = urlPrincipal+'/TFG_HUBU/Laravel/public/eliminar/paciente/'+idPaciente;
		}
		else{
			var url = urlPrincipal+'/eliminar/paciente/'+idPaciente;
		}
		var apellidos = $(element).parent().prev().text();
		var nombre = $(element).parent().prev().prev().text();
		Swal.fire({
		  title: 'Estas seguro que deseas eliminar el paciente '+nombre+' '+apellidos,
		  icon: "warning",
		  showDenyButton: true,
		  confirmButtonText: 'Eliminar',
		  denyButtonText: 'Cancerlar',
		}).then((result) => {
		  if (result.isConfirmed) {
			$.ajax({
			    url: url,
			    type: 'GET',
			    dataType: 'html',
			    success: function(result) {
			    	location.reload();
			    }
			});
		  }
		})
	}
</script>
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
