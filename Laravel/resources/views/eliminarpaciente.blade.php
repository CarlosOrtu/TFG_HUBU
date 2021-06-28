@extends('layouts.app')

@section('content')

@if (count($pacientes) > 0)
<div class="panel panel-default">
    <div class="panel-heading">
		<h1 class="text-white text-center panel-title">Listado de pacientes</h1>
    <div class=panel-body>
    	<div class="table-responsive">
	        <table id="tablaPacientes" class="table table-dark table-bordered">
	        	<caption>Pacientes</caption>
	            <thead>
	            	<tr>
	                	<th scope="col" class="left">ID Paciente</th>
	                	@if(env('APP_ENV') == 'production')
	                	<th scope="col">Número de historia clínica</th>
	                	@else
	                    <th scope="col">Número de historia clinica</th>
	                    <th scope="col">Apellidos</th>
						@endif
						<th scope="col" class="right">Seleccionar</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
	        			<td class="left">
	        				<input placeholder="ID" class="form-control mb-2 mr-2" type="text" autocomplete="off">
	        			</td>
	        			@if(env('APP_ENV') == 'production')
	        			<td>
	        				<input placeholder="NHC" class="form-control mb-2 mr-2" type="text" autocomplete="off">
	        			</td>	   
	        			@else
	        			<td>
	        				<input placeholder="Nombre" class="form-control mb-2 mr-2" type="text" autocomplete="off">
	        			</td>	                            			
	        			<td>
	        				<input placeholder="Apellidos" class="form-control mb-2 mr-2" type="text" autocomplete="off">
	         			</td>
	         			@endif
	        			<td class="right">
	        			</td>
	        		</tr>
	            </tfoot>

	            <tbody>
	                @foreach ($pacientes as $paciente)
	                    <tr>
	                    	<td class="table-text text-dark left"><div>{{ $paciente->id_paciente }}</div></td>
	                    	@if(env('APP_ENV') == 'production')
	                        <td class="table-text text-dark"><div>{{ $encriptacion->desencriptar($paciente->NHC) }}</div></td>
	                        @else
	                        <td class="table-text text-dark"><div>{{ $paciente->nombre }}</div></td>
	                        <td class="table-text text-dark"><div>{{ $paciente->apellidos }}</div></td>
	                        @endif
							<td class="right">
								<a onClick="confirmarEliminacion(this)"><button type="button" class="btn btn-warning"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 bi bi-trash" viewBox="0 0 16 16">
  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
</svg>Eliminar</button></a>
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
		var urlPrincipal = $(location).attr('origin');
		var entorno = "<?php echo env('APP_ENV');?>";
		if(entorno == "local"){
			var idPaciente = $(element).parent().prev().prev().prev().text();
			var url = urlPrincipal+'/TFG_HUBU/Laravel/public/eliminar/paciente/'+idPaciente;
			var nombre = $(element).parent().prev().prev().text();
		}
		else{
			var idPaciente = $(element).parent().prev().prev().text();
			var url = urlPrincipal+'/eliminar/paciente/'+idPaciente;
			var nombre = $(element).parent().prev().text();
		}
		Swal.fire({
		  title: 'Estas seguro que deseas eliminar el paciente con el nombre '+nombre,
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
