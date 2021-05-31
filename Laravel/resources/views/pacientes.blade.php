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
	                	@if(env('APP_ENV') == 'production')
	                	<th>Número de historia clínica</th>
	                	@else
	                    <th>Número de historia clinica</th>
	                    <th>Apellidos</th>
						@endif
						<th class="right">Seleccionar</th>
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
								<a href="{{ route('verdatospaciente', $paciente->id_paciente) }}" ><button type="button" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 bi bi-eye" viewBox="0 0 16 16">
  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
</svg>Ver</button></a>
								@if(count($paciente->Seguimientos->where('estado','Fallecido')) < 1)
								<a href="{{ route('datospaciente', $paciente->id_paciente) }}" ><button type="button" class="btn btn-info"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 bi bi-pencil" viewBox="0 0 16 16">
  <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
</svg>Editar</button></a>
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
