<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use Illuminate\Support\Facades\DB;
use DateTime;
use App\Http\Controllers\PacientesFiltradosController;

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

    private function obetenerEdadesOrdenadas($pacientesFiltrados)
    {
    	if($pacientesFiltrados != null){
            $pacientes = $pacientesFiltrados;
        }else{
           $pacientes = Pacientes::all();
        }

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

    private function obtenerCampoNumericoOrdenado($dato, $pacientesFiltrados)
    {
        if($pacientesFiltrados != null){
            $pacientesConEnfermedades = $pacientesFiltrados;
        }else{
            $pacientesConEnfermedades = DB::table('Pacientes')->join('Enfermedades', 'Pacientes.id_paciente', '=', 'Enfermedades.id_paciente')->get();
        }
    	$datos = array();
    	foreach($pacientesConEnfermedades as $paciente){
            if($pacientesFiltrados != null){
                if($paciente->Enfermedades->$dato == null){
                    array_push($datos, 0);
                }else{
                    array_push($datos, $paciente->Enfermedades->$dato);
                }
            }else{
        		if($paciente->$dato == null){
        			array_push($datos, 0);
        		}else{
        			array_push($datos, $paciente->$dato);
        		}
            }
    	}

    	sort($datos ,SORT_NUMERIC);
        
    	return $datos;
    }

    private function obtenerNumerosEnfermedadesOrdenados($dato, $pacientesFiltrados)
    {
        if($pacientesFiltrados != null){
            $pacientes = $pacientesFiltrados;
        }else{
           $pacientes = Pacientes::all();
        }
        $datos = array();
    	foreach($pacientes as $paciente){
    		array_push($datos, count($paciente->Enfermedades->$dato));
    	}

    	sort($datos ,SORT_NUMERIC);

    	return $datos;
    }

    private function obtenerNumerosPacientesOrdenados($dato, $pacientesFiltrados)
    {
        if($pacientesFiltrados != null){
            $pacientes = $pacientesFiltrados;
        }else{
           $pacientes = Pacientes::all();
        }
        $datos = array();
    	foreach($pacientes as $paciente){
    		array_push($datos, count($paciente->$dato));
    	}

    	sort($datos ,SORT_NUMERIC);

    	return $datos;
    }

    private function obtenerNumerosEnfermedadesFamiliar($pacientesFiltrados)
    {
        if($pacientesFiltrados != null){
            $pacientes = $pacientesFiltrados;
        }else{
           $pacientes = Pacientes::all();
        }
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

    private function obetenerNumerosTiposDeTratamiento($dato, $pacientesFiltrados)
    {
        if($pacientesFiltrados != null){
            $pacientes = $pacientesFiltrados;
        }else{
           $pacientes = Pacientes::all();
        }
        $datos = array();
    	foreach($pacientes as $paciente){
    		array_push($datos, count($paciente->Tratamientos->where('tipo',$dato)));
    	}
    	sort($datos ,SORT_NUMERIC);

    	return $datos;
    }

    private function obetenerNumerosCiclos($pacientesFiltrados)
    {
        if($pacientesFiltrados != null){
            $pacientes = $pacientesFiltrados;
        }else{
    	   $pacientes = Pacientes::all();
        }
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

    private function obetenerNumerosDosis($pacientesFiltrados)
    {
        if($pacientesFiltrados != null){
            $pacientes = $pacientesFiltrados;
        }else{
           $pacientes = Pacientes::all();
        }
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

   	private function obtenerListaOrdenada($dato, $pacientesFil)
   	{
    	if($dato == 'edad'){
    		return $this->obetenerEdadesOrdenadas($pacientesFil);
    	}
    	if($dato == 'num_tabaco_dia' || $dato == 'T_tamano'){
    		return $this->obtenerCampoNumericoOrdenado($dato, $pacientesFil);
    	}
    	if($dato == 'Sintomas' || $dato == 'Metastasis' || $dato == 'Biomarcadores' || $dato == 'Pruebas_realizadas' || $dato == 'Tecnicas_realizadas' || $dato == 'Otros_tumores'){
			return $this->obtenerNumerosEnfermedadesOrdenados($dato, $pacientesFil);
    	}
    	if($dato == 'Antecedentes_medicos' || $dato == 'Antecedentes_oncologicos' || $dato == 'Antecedentes_familiares' || $dato == 'Tratamientos' || $dato == 'Reevaluaciones' || $dato == 'Seguimientos'){
    		return $this->obtenerNumerosPacientesOrdenados($dato, $pacientesFil);
    	}
    	if($dato == 'Enfermedades_familiar'){
    		return $this->obtenerNumerosEnfermedadesFamiliar($pacientesFil);
    	}
    	if($dato == 'Quimioterapia' || $dato == 'Radioterapia' || $dato == 'Cirugia'){
    		return $this->obetenerNumerosTiposDeTratamiento($dato, $pacientesFil);
    	}
    	if($dato == 'numero_ciclos')
    		return $this->obetenerNumerosCiclos($pacientesFil);

    	return $this->obetenerNumerosDosis($pacientesFil);

   	}

    public function imprimirPercentiles(Request $request, $opciones = null)
    {
        try{
            if($opciones != null){
                $opcionesArray = explode("-", $opciones);
                $pacientesFil = app(PacientesFiltradosController::class)->obtenerPacientesFiltrados($opcionesArray);
            }else{
                $pacientesFil = null;
            }
        	$dato = $request->datosPercentil;
        	$listaOrdenada = $this->obtenerListaOrdenada($dato, $pacientesFil);
        	$media = $this->calcularMedia($listaOrdenada);
        	$desviacion = $this->calcularDesviacionTipica($listaOrdenada, $media);
        	$skewness = $this->calcularSkewness($listaOrdenada, $media, $desviacion);
        	$kurtosis = $this->calcularKurtosis($listaOrdenada, $media, $desviacion);
        	$arrayPercentiles = [$this->calcularPercentil($listaOrdenada, 1), $this->calcularPercentil($listaOrdenada, 5), $this->calcularPercentil($listaOrdenada, 10), $this->calcularPercentil($listaOrdenada, 25), $this->calcularPercentil($listaOrdenada, 50), $this->calcularPercentil($listaOrdenada, 75), $this->calcularPercentil($listaOrdenada, 90), $this->calcularPercentil($listaOrdenada, 95), $this->calcularPercentil($listaOrdenada, 99)];
        	return view('mostrarpercentiles', ['opciones' => $opciones,'datosGraficaPercentil' => $listaOrdenada, 'tipoDato' => $dato, 'media' => $media, 'desviacion' => $desviacion, 'skewness' => $skewness, 'kurtosis' => $kurtosis, 'percentiles' => $arrayPercentiles]);
        }catch (\Exception $e){
            return redirect()->route('verpercentiles')->with('errorNoExisteCampo','No hay ningun dato guardado con ese atributo');
        }
    }
}