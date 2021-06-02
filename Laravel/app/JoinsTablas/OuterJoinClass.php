<?php

namespace App\JoinsTablas;

use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Otros_tumores;
use App\Models\Tecnicas_realizadas;
use App\Models\Pruebas_realizadas;
use App\Models\Biomarcadores;
use App\Models\Sintomas;
use App\Models\Metastasis;
use App\Models\Antecedentes_medicos;
use App\Models\Seguimientos;
use App\Models\Antecedentes_oncologicos;
use App\Models\Antecedentes_familiares;
use App\Models\Enfermedades_familiar;
use App\Models\Reevaluaciones;
use App\Models\Tratamientos;
use App\Models\Intenciones;
use App\Models\Farmacos;
use Illuminate\Support\Facades\DB;


class OuterJoinClass 
{
    public function joinTodasTablas()
    {
    	return DB::table('Pacientes')->leftJoin('Enfermedades','Pacientes.id_paciente','=','Enfermedades.id_paciente')->leftJoin('Otros_tumores','Enfermedades.id_enfermedad','=','Otros_tumores.id_enfermedad')->leftJoin('Tecnicas_realizadas','Enfermedades.id_enfermedad','=','Tecnicas_realizadas.id_enfermedad')->leftJoin('Pruebas_realizadas','Enfermedades.id_enfermedad','=','Pruebas_realizadas.id_enfermedad')->leftJoin('Biomarcadores','Enfermedades.id_enfermedad','=','Biomarcadores.id_enfermedad')->leftJoin('Sintomas','Enfermedades.id_enfermedad','=','Sintomas.id_enfermedad')->leftJoin('Metastasis','Enfermedades.id_enfermedad','=','Metastasis.id_enfermedad')->leftJoin('Antecedentes_medicos','Pacientes.id_paciente','=','Antecedentes_medicos.id_paciente')->leftJoin('Seguimientos','Pacientes.id_paciente','=','Seguimientos.id_paciente')->leftJoin('Antecedentes_oncologicos','Pacientes.id_paciente','=','Antecedentes_oncologicos.id_paciente')->leftJoin('Antecedentes_familiares','Pacientes.id_paciente','=','Antecedentes_familiares.id_paciente')->leftJoin('Enfermedades_familiar','Antecedentes_familiares.id_antecedente_f','=','Enfermedades_familiar.id_antecedente_f')->leftJoin('Reevaluaciones','Pacientes.id_paciente','=','Reevaluaciones.id_paciente')->leftJoin('Tratamientos', 'Pacientes.id_paciente', '=', 'Tratamientos.id_paciente')->leftJoin('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento')->leftJoin('Farmacos','Intenciones.id_intencion','=','Farmacos.id_intencion');

    }
}