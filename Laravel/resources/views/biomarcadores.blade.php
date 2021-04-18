@extends('layouts.app')

@section('content')
<h1 class="text-white text-center panel-title">Biomarcadores</h1>
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="text-dark close" data-dismiss="alert">x</button>
    <strong class="text-center text-dark">{{ $message }}</strong>
</div>
@endif
<form action="{{ route('biomarcadores', ['id' => $paciente->id_paciente]) }}" method="post">
  @CSRF
  <div class="form-row mt-5 mb-3">
    <div class="col-sm-2">
      <div class="form-check">
        <input name="NGS" class="casilla form-check-input" type="checkbox" num_input="2" id="NGS">
        <label class="form-check-label text-white" for="NGS">
          NGS
        </label>
      </div>
    </div>
    <div id="NGS_tipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Tipo de muestra</span>
        </div>
        <select name="NGS_tipo" class="form-control">
          <option>Biopsia solida</option>
          <option>Biopsia liquida</option>
        </select>
      </div>
    </div>
    <div id="NGS_subtipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Panel</span>
        </div>
        <select name="NGS_subtipo" class="form-control">
          <option>Oncomine Focus</option>
          <option>Oncodeep</option>
          <option>Guardant 360</option>
          <option>Focus DX</option>
          <option>FoliguidLDX</option>
        </select>
      </div>
    </div>
  </div>
  <div class="form-row mb-3">
    <div class="col-sm-2">
      <div class="form-check">
        <input name="PDL1" class="casilla form-check-input" type="checkbox" num_input="2" id="PDL1">
        <label class="form-check-label text-white" for="PDL1">
          PDL1
        </label>
      </div>
    </div>
    <div id="PDL1_tipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Técnica</span>
        </div>
        <select name="PDL1_tipo" class="form-control">
          <option>En célula tumorales con IHQ</option>
          <option>Score copmbinado/CPS</option>
        </select>
      </div>
    </div>
    <div id="PDL1_subtipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Valor</span>
        </div>
        <select name="PDL1_subtipo" class="form-control">
          <option>&le; 1%</option>
          <option>1-49%</option>
          <option>&ge; 50%</option>
        </select>
      </div>
    </div>
  </div>
  <div class="form-row mb-3">
    <div class="col-sm-2">
      <div class="form-check">
        <input name="EGFR" class="casilla form-check-input" type="checkbox" num_input="1" id="EGFR">
        <label class="form-check-label text-white" for="EGFR">
          EGFR
        </label>
      </div>
    </div>
    <div id="EGFR_tipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
        </div>
        <select id="EGFR_tipo_input" name="EGFR_tipo" class="input_cambio form-control">
          <option>Exón 19</option>
          <option>Exón 21</option>
          <option>Exón 20</option>
          <option>Exón 18</option>
          <option>T790M positivo</option>
          <option>T790M negativo</option>
          <option>Otra</option>
        </select>
      </div>
    </div>
  </div>
  <div id="EGFR_tipo_input_especificar" class="oculto offset-sm-2 col-sm-5">
    <div class=" ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar tipo</span>
      </div>
      <input name="EGFR_tipo_especificar" class="form-control" autocomplete="off">
    </div>
  </div>
  <div class="form-row mb-3">
    <div class="col-sm-2">
      <div class="form-check">
        <input name="ALK" class="casilla form-check-input" type="checkbox" num_input="1" id="ALK">
        <label class="form-check-label text-white" for="ALK">
          ALK
        </label>
      </div>
    </div>
    <div id="ALK_tipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
        </div>
        <select id="ALK_tipo_input" name="ALK_tipo" otro_imput="Traslocado" subtipo="ALK_subtipo" class="input_cambio form-control">
          <option>Traslocado</option>
          <option>No traslocado</option>
          <option>Mutación</option>
          <option>Otra</option>
        </select>
      </div>
    </div>
    <div id="ALK_subtipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Subtipo</span>
        </div>
        <select id="ALK_subtipo_input" name="ALK_subtipo" class="input_cambio form-control">
          <option>Gusión EML4</option>
          <option>Otra</option>
        </select>
      </div>
    </div>
  </div>
  <div class="form-row">
    <div id="ALK_tipo_input_especificar" class="oculto offset-sm-2 col-sm-4">
      <div class=" ml-2 my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Especificar tipo</span>
        </div>
        <input name="ALK_tipo_especificar" class="form-control" autocomplete="off">
      </div>
    </div>
    <div id="ALK_subtipo_input_especificar" class="oculto offset-sm-7 col-sm-4">
      <div class=" ml-2 my-4 input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Especificar <br>subtipo</span>
        </div>
        <input name="ALK_subtipo_especificar" class="form-control" autocomplete="off">
      </div>
    </div>
  </div>
  <div class="form-row mb-3">
    <div class="col-sm-2">
      <div class="form-check">
        <input name="ROS1" class="casilla form-check-input" type="checkbox" num_input="1" id="ROS1">
        <label class="form-check-label text-white" for="ROS1">
          ROS1
        </label>
      </div>
    </div>
    <div id="ROS1_tipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
        </div>
        <select name="ROS1_tipo" class="form-control">
          <option>Traslocado</option>
          <option>No traslocado</option>
        </select>
      </div>
    </div>
  </div>
  <div class="form-row mb-3">
    <div class="col-sm-2">
      <div class="form-check">
        <input name="KRAS" class="casilla form-check-input" type="checkbox" num_input="1" id="KRAS">
        <label class="form-check-label text-white" for="KRAS">
          KRAS
        </label>
      </div>
    </div>
    <div id="KRAS_tipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
        </div>
        <select id="KRAS_tipo_input" name="KRAS_tipo" otro_imput="Mutado" subtipo="KRAS_subtipo" class="input_cambio form-control">
          <option>Mutado</option>
          <option>No mutado</option>
        </select>
      </div>
    </div>
    <div id="KRAS_subtipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Subtipo</span>
        </div>
        <select id="KRAS_subtipo_input" name="KRAS_subtipo" class="input_cambio form-control">
          <option>KRASG12C</option>
          <option>KRASG12V</option>
          <option>Otra</option>
        </select>
      </div>
    </div>
  </div>
  <div id="KRAS_subtipo_input_especificar" class="oculto offset-sm-7 col-sm-5">
    <div class=" ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar <br>subtipo</span>
      </div>
      <input name="KRAS_subtipo_especificar" class="form-control" autocomplete="off">
    </div>
  </div>
  <div class="form-row mb-3">
    <div class="col-sm-2">
      <div class="form-check">
        <input name="BRAF" class="casilla form-check-input" type="checkbox" num_input="1" id="BRAF">
        <label class="form-check-label text-white" for="BRAF">
          BRAF
        </label>
      </div>
    </div>
    <div id="BRAF_tipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
        </div>
        <select id="BRAF_tipo_input" name="BRAF_tipo" otro_imput="Mutado" subtipo="BRAF_subtipo" class="input_cambio form-control">
          <option>Mutado</option>
          <option>No mutado</option>
          <option>Clase I</option>
          <option>Clase II</option>
          <option>Clase III</option>
        </select>
      </div>
    </div>
    <div id="BRAF_subtipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Subtipo</span>
        </div>
        <select id="BRAF_subtipo_input" name="BRAF_subtipo" class="input_cambio form-control">
          <option>BRAFV600E</option>
          <option>Otra</option>
        </select>
      </div>
    </div>
  </div>
  <div id="BRAF_subtipo_input_especificar" class="oculto offset-sm-7 col-sm-5">
    <div class=" ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar <br>subtipo</span>
      </div>
      <input name="BRAF_subtipo_especificar" class="form-control" autocomplete="off">
    </div>
  </div>
  <div class="form-row mb-3">
    <div class="col-sm-2">
      <div class="form-check">
        <input name="HER2" class="casilla form-check-input" type="checkbox" num_input="1" id="HER2">
        <label class="form-check-label text-white" for="HER2">
          HER2
        </label>
      </div>
    </div>
    <div id="HER2_tipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
        </div>
        <select name="HER2_tipo" class="form-control">
          <option>Mutado</option>
          <option>Amplificado</option>
          <option>No alterado</option>
        </select>
      </div>
    </div>
  </div>
  <div class="form-row mb-3">
    <div class="col-sm-2">
      <div class="form-check">
        <input name="NTRK" class="casilla form-check-input" type="checkbox" num_input="2" id="NTRK">
        <label class="form-check-label text-white" for="NTRK">
          NTRK
        </label>
      </div>
    </div>
    <div id="NTRK_tipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
        </div>
        <select name="NTRK_tipo" class="form-control">
          <option>Mutado</option>
          <option>No mutado</option>
          <option>Reordenado</option>
        </select>
      </div>
    </div>
    <div id="NTRK_subtipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Subtipo</span>
        </div>
        <select name="NTRK_subtipo" class="form-control">
          <option>NTRK 1</option>
          <option>NTRK 2</option>
          <option>NTRK 3</option>
        </select>
      </div>
    </div>
  </div>
  <div class="form-row mb-3">
    <div class="col-sm-2">
      <div class="form-check">
        <input name="FGFR1" class="casilla form-check-input" type="checkbox" num_input="2" id="FGFR1">
        <label class="form-check-label text-white" for="FGFR1">
          FGFR1
        </label>
      </div>
    </div>
    <div id="FGFR1_tipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
        </div>
        <select name="FGFR1_tipo" class="form-control">
          <option>Amplificado</option>
          <option>No amplificado</option>
          <option>Mutación</option>
        </select>
      </div>
    </div>
    <div id="FGFR1_tipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Subtipo</span>
        </div>
        <select name="FGFR1_tipo" class="form-control">
          <option>FGFR 1</option>
          <option>FGFR 2</option>
          <option>FGFR 3</option>
        </select>
      </div>
    </div>
  </div>
  <div class="form-row mb-3">
    <div class="col-sm-2">
      <div class="form-check">
        <input name="RET" class="casilla form-check-input" type="checkbox" num_input="1" id="RET">
        <label class="form-check-label text-white" for="RET">
          RET
        </label>
      </div>
    </div>
    <div id="RET_tipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
        </div>
        <select name="RET_tipo" class="form-control">
          <option>Traslocado</option>
          <option>No traslocado</option>
          <option>Mutación</option>
        </select>
      </div>
    </div>
  </div>
  <div class="form-row mb-3">
    <div class="col-sm-2">
      <div class="form-check">
        <input name="MET" class="casilla form-check-input" type="checkbox" num_input="1" id="MET">
        <label class="form-check-label text-white" for="MET">
          MET
        </label>
      </div>
    </div>
    <div id="MET_tipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
        </div>
        <select name="MET_tipo" class="form-control">
          <option>Mutación Exón 14</option>
          <option>No mutado</option>
          <option>Amplificado</option>
          <option>Sobreexpresado</option>
        </select>
      </div>
    </div>
  </div>
  <div class="form-row mb-3">
    <div class="col-sm-2">
      <div class="form-check">
        <input name="Pl3K" class="casilla form-check-input" type="checkbox" num_input="1" id="Pl3K">
        <label class="form-check-label text-white" for="Pl3K">
          Pl3K
        </label>
      </div>
    </div>
    <div id="Pl3K_tipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
        </div>
        <select name="Pl3K_tipo" class="form-control">
          <option>Mutado</option>
          <option>No mutado</option>
        </select>
      </div>
    </div>
  </div>
  <div class="form-row mb-3">
    <div class="col-sm-2">
      <div class="form-check">
        <input name="TMB" class="casilla form-check-input" type="checkbox" num_input="1" id="TMB">
        <label class="form-check-label text-white" for="TMB">
          TMB
        </label>
      </div>
    </div>
    <div id="TMB_tipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
        </div>
        <select name="TMB_tipo" class="form-control">
          <option>Alto</option>
          <option>Bajo</option>
        </select>
      </div>
    </div>
  </div>
  <div class="form-row">
    <div class="col-sm-2">
      <div class="form-check">
        <input name="Otros" class="casilla form-check-input" type="checkbox" num_input="1" id="Otros">
        <label class="form-check-label text-white" for="Otros">
          Otros 
        </label>
      </div>
    </div>
    <div id="Otros_tipo" class="oculto col-sm-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">Tipo</span>
        </div>
        <select id="Otros_tipo_input" name="Otros_tipo" class="input_cambio form-control">
          <option>BRCA1</option>
          <option>BRCA2</option>
          <option>KIT</option>
          <option>JAK</option>
          <option>STK11</option>
          <option>ARID1A</option>
          <option>smoothedend</option>
          <option>Otra</option>
        </select>
      </div>
    </div>
  </div>
  <div id="Otros_tipo_input_especificar" class="oculto offset-sm-2 col-sm-5">
    <div class=" ml-2 my-4 input-group">
      <div class="input-group-prepend">
          <span class="input-group-text">Especificar <br>subtipo</span>
      </div>
      <input name="Otros_tipo_especificar" class="form-control" autocomplete="off">
    </div>
  </div>
  <div class="d-flex justify-content-center mb-4">
    <button type="submit" class="btn btn-primary">Guardar</button>
  </div>
</form>
<div class="dropdown-divider"></div>
<?php
    $i = 0;
?>
@if (count($biomarcadores) > 0)
<div class="panel panel-default">
  <div class="panel-heading">
    <h1 class="text-white text-center panel-title">Listado de biomarcadores</h1>
    <div class=panel-body>
      <div class="table-responsive">
        <table class="table dataTable biomarcadores">
          <thead class="text-white">
            <tr>
              <th class="left">Nombre</th>
              <th>Tipo</th>
              <th>Subtipo</th>
              <th class="right">Eliminar</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($biomarcadores as $biomarcador)
            <form action="{{ route('eliminarbiomarcador', ['id' => $paciente->id_paciente, 'num_biomarcador' => $i]) }}" method="post">>
            @CSRF
            @method('delete')
              <tr>
                <td class="table-text text-dark left"><div>{{ $biomarcador->nombre }}</div></td>
                @if(preg_match("/^Otro: /", $biomarcador->tipo) )
                <td class="table-text text-dark"><div>{{ substr($biomarcador->tipo, 6) }}</div></td>
                @else
                <td class="table-text text-dark"><div>{{ $biomarcador->tipo }}</div></td>
                @endif
                @if(preg_match("/^Otro: /", $biomarcador->subtipo) )
                <td class="table-text text-dark"><div>{{ substr($biomarcador->subtipo, 6) }}</div></td>
                @else
                <td class="table-text text-dark"><div>{{ $biomarcador->subtipo }}</div></td>
                @endif
                <td class="right">
                  <button type="submit" class="btn btn-primary">Eliminar</button>
                </td>
              </tr>
              <?php
                  $i = $i + 1;
              ?>
            </form>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@else
<h1 class="text-white panel-title">No hay biomarcadores</h1>
@endif
<script type="text/javascript" src="{{ asset('/js/biomarcadores.js') }}"></script>
@endsection