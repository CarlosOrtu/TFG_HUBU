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
	                	<th>ID Usuario</th>
	                    <th>Nombre</th>
	                    <th>Apellidos</th>
						<th>Seleccionar</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
	        			<td>
	        				<input placeholder="ID" class="form-control mb-2 mr-2" type="text" autocomplete="off">
	        			</td>
	        			<td>
	        				<input placeholder="Nombre" class="form-control mb-2 mr-2" type="text" autocomplete="off">
	        			</td>	                            			
	        			<td>
	        				<input placeholder="Apellidos" class="form-control mb-2 mr-2" type="text" autocomplete="off">
	        			</td>
	        			<td>
	        			</td>
	        		</tr>
	            </tfoot>

	            <tbody>
	                @foreach ($usuarios as $usuario)
	                    <tr>
	                    	<td class="table-text text-dark"><div>{{ $usuario->id_user }}</div></td>
	                        <td class="table-text text-dark"><div>{{ $usuario->name }}</div></td>
	                        <td class="table-text text-dark"><div>{{ $usuario->surname }}</div></td>
							<td>
								<form class="d-inline-block" action="{{ route('modificarusuario', ['id' => $usuario->id_user]) }}" method="get">
									<button class="btn btn-primary">Editar</button>
								</form>
								@if($usuario->id_role != 1)
								<form class="d-inline-block" action="{{ route('eliminarusuario', ['id' => $usuario->id_user]) }}" method="post">
									@csrf
									@method('delete')
									<button type="submit" class="btn btn-warning">Eliminar</button>
								</form>
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
