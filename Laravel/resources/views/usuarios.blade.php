@extends('layouts.app')

@section('content')

@if (count($usuarios) > 0)
<div class="panel panel-default">
    <div class="panel-heading">
		<h1 class="text-white text-center panel-title">Listado de usuarios</h1>
    <div class=panel-body>
    	<div class="table-responsive">
	        <table id="tablaPacientes" class="table table-dark table-bordered">
	        	<thead>
	            	<tr>
	                	<th class="left" >ID Usuario</th>
	                    <th style="border-top-width: 0">Nombre</th>
	                    <th style="border-top-width: 0">Apellidos</th>
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
	                @foreach ($usuarios as $usuario)
	                    <tr>
	                    	<td class="table-text text-dark left"><div>{{ $usuario->id_usuario }}</div></td>
	                        <td class="table-text text-dark"><div>{{ $usuario->nombre }}</div></td>
	                        <td class="table-text text-dark"><div>{{ $usuario->apellidos }}</div></td>
							<td class="right">
								<form class="d-inline-block" action="{{ route('modificarusuario', ['id' => $usuario->id_usuario]) }}" method="get">
									<button class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 bi bi-pencil" viewBox="0 0 16 16">
  <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
</svg>Editar</button>
								</form>
								@if($usuario->id_rol != 1)
								<button type="submit" onClick="confirmarEliminacion(this)" class="btn btn-warning"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 bi bi-trash" viewBox="0 0 16 16">
  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
</svg>Eliminar</button>
								@endif
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
		var idUsuario = $(element).parent().prev().prev().prev().text();
		var urlPrincipal = $(location).attr('origin');
		var entorno = "<?php echo env('APP_ENV');?>";
		if(entorno == "local"){
			var url = urlPrincipal+'/TFG_HUBU/Laravel/public/eliminar/usuario/'+idUsuario;
		}
		else{
			var url = urlPrincipal+'/eliminar/usuario/'+idUsuario;
		}
		var apellidos = $(element).parent().prev().text();
		var nombre = $(element).parent().prev().prev().text();
		Swal.fire({
		  title: 'Estas seguro que deseas eliminar el usuario '+nombre+' '+apellidos,
		  icon: "warning",
		  showDenyButton: true,
		  confirmButtonText: 'Eliminar',
		  denyButtonText: 'Cancerlar',
		}).then((result) => {
		  if (result.isConfirmed) {
			$.ajax({
			    url: url,
			    dataType: 'html',
			    type: 'GET', 
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
