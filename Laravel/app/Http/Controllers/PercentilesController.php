<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use Illuminate\Support\Facades\DB;
use DateTime;

class PercentilesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function verPercentiles()
    {
    	return view('percentiles');
    }

    private function obetenerEdadesOrdenadas()
    {
    	$pacientes = Pacientes::all();

    	$edades = array();
    	foreach($pacientes as $paciente){
    		$nacimiento = $paciente->nacimiento;
			$nacimiento = new DateTime($nacimiento);
			$actual = new DateTime(date("Y-m-d"));
			$diferencia = $actual->diff($nacimiento);
			array_push($edades, $diferencia->format("%y"));
    	}

    	sort($edades ,SORT_NUMERIC);
    	return $edades;
    }

    private function calcularMedia($listaOrdenada)
    {
    	$suma = array_sum($listaOrdenada);
    	$numValores = count($listaOrdenada);

    	return $suma/$numValores;
    }

    private function calcularDesviacionTipica($listaOrdenada, $media)
    {
    	$sumatorioDistancias = 0;
    	foreach($listaOrdenada as $dato){
    		$sumatorioDistancias += pow(abs($dato - $media), 2);
    	}

    	return sqrt($sumatorioDistancias/(count($listaOrdenada) -1 ));
    }

    private function calcularSkewness($listaOrdenada, $media, $desviacion)
    {
    	$sumatorioDistancias = 0;
    	foreach($listaOrdenada as $dato){
    		$sumatorioDistancias += pow($dato - $media, 3);
    	}
    	if($desviacion == 0)
    		return 0;
    	return $sumatorioDistancias/((count($listaOrdenada) -1) * pow($desviacion, 3));
    }

    private function calcularKurtosis($listaOrdenada, $media, $desviacion)
    {
    	$sumatorioDistancias = 0;
    	foreach($listaOrdenada as $dato){
    		$sumatorioDistancias += pow($dato - $media, 4);
    	}
    	if($desviacion == 0)
    		return 0;
    	return $sumatorioDistancias/((count($listaOrdenada) -1) * pow($desviacion, 4));
    }

    private function calcularPercentil($listaOrdenada, $percentil)
    {
    	$indice = ($percentil/100)*count($listaOrdenada);
    	$indiceRedondeado = ceil($indice);

    	return $listaOrdenada[$indiceRedondeado - 1];
    }

    private function obtenerCampoNumericoOrdenado($dato)
    {
    	$pacientesConEnfermedades = DB::table('Pacientes')->join('Enfermedades', 'Pacientes.id_paciente', '=', 'Enfermedades.id_paciente');
    	$datos = array();
    	foreach($pacientesConEnfermedades->get() as $paciente){
    		if($paciente->$dato == null){
    			array_push($datos, 0);
    		}else{
    			array_push($datos, $paciente->$dato);
    		}
    	}

    	sort($datos ,SORT_NUMERIC);

    	return $datos;
    }

    private function obtenerNumerosEnfermedadesOrdenados($dato)
    {
    	$pacientes = Pacientes::all();
    	$datos = array();
    	foreach($pacientes as $paciente){
    		array_push($datos, count($paciente->Enfermedades->$dato));
    	}

    	sort($datos ,SORT_NUMERIC);

    	return $datos;
    }

    private function obtenerNumerosPacientesOrdenados($dato)
    {
    	$pacientes = Pacientes::all();
    	$datos = array();
    	foreach($pacientes as $paciente){
    		array_push($datos, count($paciente->$dato));
    	}

    	sort($datos ,SORT_NUMERIC);

    	return $datos;
    }

    private function obtenerNumerosEnfermedadesFamiliar()
    {
    	$pacientes = Pacientes::all();
    	$datos = array();
    	foreach($pacientes as $paciente){
    		$totalEnfermedades = 0;
    		foreach($paciente->Antecedentes_familiares as $antecendeteFamiliar){
    			$totalEnfermedades += count($antecendeteFamiliar->Enfermedades_familiar);
    		}
    		array_push($datos, $totalEnfermedades);
    	}
    	sort($datos ,SORT_NUMERIC);

    	return $datos;
    }

    private function obetenerNumerosTiposDeTratamiento($dato)
    {
    	$pacientes = Pacientes::all();
    	$datos = array();
    	foreach($pacientes as $paciente){
    		array_push($datos, count($paciente->Tratamientos->where('tipo',$dato)));
    	}
    	sort($datos ,SORT_NUMERIC);

    	return $datos;
    }

    private function obetenerNumerosCiclos()
    {
    	$pacientes = Pacientes::all();
    	$datos = array();
    	foreach($pacientes as $paciente){
    		foreach($paciente->Tratamientos as $tratamiento){
    			if(isset($tratamiento->Intenciones)){
	    			if($tratamiento->Intenciones->first()->numero_ciclos != null){
	    				array_push($datos, $tratamiento->Intenciones->first()->numero_ciclos);
	    			}
    			}
    		}
    	}
    	sort($datos ,SORT_NUMERIC);
    	return $datos;
    }

    private function obetenerNumerosDosis()
    {
    	$pacientes = Pacientes::all();
    	$datos = array();
    	foreach($pacientes as $paciente){
    		foreach($paciente->Tratamientos as $tratamiento){
    			if($tratamiento->dosis != null){
    				array_push($datos, $tratamiento->dosis);
    			}
    		}
    	}
    	sort($datos ,SORT_NUMERIC);

    	return $datos;
    }

   	private function obtenerListaOrdenada($dato)
   	{
    	if($dato == 'edad'){
    		return $this->obetenerEdadesOrdenadas();
    	}
    	if($dato == 'num_tabaco_dia' || $dato == 'T_tamano'){
    		return $this->obtenerCampoNumericoOrdenado($dato);
    	}
    	if($dato == 'Sintomas' || $dato == 'Metastasis' || $dato == 'Biomarcadores' || $dato == 'Pruebas_realizadas' || $dato == 'Tecnicas_realizadas' || $dato == 'Otros_tumores'){
			return $this->obtenerNumerosEnfermedadesOrdenados($dato);
    	}
    	if($dato == 'Antecedentes_medicos' || $dato == 'Antecedentes_oncologicos' || $dato == 'Antecedentes_familiares' || $dato == 'Tratamientos' || $dato == 'Reevaluaciones' || $dato == 'Seguimientos'){
    		return $this->obtenerNumerosPacientesOrdenados($dato);
    	}
    	if($dato == 'Enfermedades_familiar'){
    		return $this->obtenerNumerosEnfermedadesFamiliar();
    	}
    	if($dato == 'Quimioterapia' || $dato == 'Radioterapia' || $dato == 'Cirugia'){
    		return $this->obetenerNumerosTiposDeTratamiento($dato);
    	}
    	if($dato == 'numero_ciclos')
    		return $this->obetenerNumerosCiclos();

    	return $this->obetenerNumerosDosis();

   	}

    public function imprimirPercentiles(Request $request)
    {
    	$dato = $request->datosPercentil;
    	$listaOrdenada = $this->obtenerListaOrdenada($dato);
    	$media = $this->calcularMedia($listaOrdenada);
    	$desviacion = $this->calcularDesviacionTipica($listaOrdenada, $media);
    	$skewness = $this->calcularSkewness($listaOrdenada, $media, $desviacion);
    	$kurtosis = $this->calcularKurtosis($listaOrdenada, $media, $desviacion);
    	$arrayPercentiles = [$this->calcularPercentil($listaOrdenada, 1), $this->calcularPercentil($listaOrdenada, 5), $this->calcularPercentil($listaOrdenada, 10), $this->calcularPercentil($listaOrdenada, 25), $this->calcularPercentil($listaOrdenada, 50), $this->calcularPercentil($listaOrdenada, 75), $this->calcularPercentil($listaOrdenada, 90), $this->calcularPercentil($listaOrdenada, 95), $this->calcularPercentil($listaOrdenada, 99)];
    	return view('mostrarpercentiles', ['datosGraficaPercentil' => $listaOrdenada, 'tipoDato' => $dato, 'media' => $media, 'desviacion' => $desviacion, 'skewness' => $skewness, 'kurtosis' => $kurtosis, 'percentiles' => $arrayPercentiles]);
    }

}