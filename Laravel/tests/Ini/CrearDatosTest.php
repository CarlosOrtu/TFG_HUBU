<?php

namespace Tests\Ini;

use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Reevaluaciones;
use App\Models\Seguimientos;
use App\Models\Comentarios;

class CrearDatosTest
{
	public function crearPaciente() {
                $paciente = new Pacientes();
                $paciente->id_paciente = 999;
                $paciente->nombre = "PacienteTest";
                $paciente->apellidos = "ApellidosTest";
                $paciente->sexo = "Masculino";
                $paciente->nacimiento = "1999-10-05";
                $paciente->raza = "Asiático";      
                $paciente->profesion = "Peluquero";      
                $paciente->fumador = "Desconocido";      
                $paciente->bebedor = "Desconocido";      
                $paciente->carcinogenos = "Desconocido"; 
                $paciente->ultima_modificacion = date("Y-m-d");
                $paciente->save(); 
	}

	public function crearEnfermedad() {
                $enfermedad = new Enfermedades();
                $enfermedad->id_enfermedad = 999;
                $enfermedad->id_paciente = 999;
                $enfermedad->fecha_primera_consulta = "1999-02-02";
                $enfermedad->fecha_diagnostico = "1999-03-03";
                $enfermedad->ECOG = 2;
                $enfermedad->T = 3;
                $enfermedad->T_tamano = 1.0;
                $enfermedad->N = 3;
                $enfermedad->N_afectacion = "Uni ganglionar";
                $enfermedad->M = "1b";
                $enfermedad->num_afec_metas = "1";
                $enfermedad->TNM = "IA2";
                $enfermedad->tipo_muestra = "Biopsia";
                $enfermedad->histologia_tipo = "Sarcomatoide";
                $enfermedad->histologia_subtipo = "Mucinoso";
                $enfermedad->histologia_grado = "Bien diferenciado";
                $enfermedad->tratamiento_dirigido = 1;
                $enfermedad->save();
	}

        public function crearReevaluacion() {
                $reeEliminar = new Reevaluaciones();
                $reeEliminar->id_paciente = 999;
                $reeEliminar->id_reevaluacion = 999;
                $reeEliminar->fecha = "1998-05-05";
                $reeEliminar->estado = "Respuesta parcial";
                $reeEliminar->save();
        }

        public function crearSeguimiento() {
                $seguimientoAEliminar = new Seguimientos();
                $seguimientoAEliminar->id_paciente = 999;
                $seguimientoAEliminar->id_seguimiento = 999;
                $seguimientoAEliminar->fecha = "1998-05-05";
                $seguimientoAEliminar->estado = "1998-05-05";
                $seguimientoAEliminar->save();
        }

        public function crearComentario() {
                $comentarioAEliminar = new Comentarios();
                $comentarioAEliminar->id_paciente = 999;
                $comentarioAEliminar->id_comentario = 999;
                $comentarioAEliminar->comentario = "Este es el comentario a eliminar";
                $comentarioAEliminar->save();
        }
}