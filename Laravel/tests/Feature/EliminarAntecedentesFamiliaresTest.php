<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Antecedentes_familiares;
use App\Models\Enfermedades_familiar;

class EliminarAntecedentesFamiliaresTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario 
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
        //Creamos la enfermedad
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
        //crear el antecedente familiar a eliminar
        $antecedenteFamiliarAEliminar = new Antecedentes_familiares();
        $antecedenteFamiliarAEliminar->id_paciente = 999;
        $antecedenteFamiliarAEliminar->id_antecedente_f = 999;
        $antecedenteFamiliarAEliminar->familiar = "Madre";
        $antecedenteFamiliarAEliminar->save();
        //Crear la enfermedad del familiar a eliminar
        $enfermedadFamiliarAEliminar = new Enfermedades_familiar();
        $enfermedadFamiliarAEliminar->id_antecedente_f = 999;
        $enfermedadFamiliarAEliminar->id_enfermedad_f = 999;
        $enfermedadFamiliarAEliminar->tipo = "Pulmón";
        $enfermedadFamiliarAEliminar->save();
        //Realizamos el login con el administrador para poder acceder a todos las rutas de la web
        $response = $this->get('/login')->assertSee('Login');
        $credentials = [
            "email" => "administrador@gmail.com",
            "password" => "1234",
        ];
        $response = $this->post('/login', $credentials);
    }

    protected function tearDown(): void
    {
        //Eliminamos el usuario
        $usuario = Pacientes::find(999)->delete();
        parent::tearDown();
    }

    /** @test */
    //Caso de prueba 1
    public function eliminarAntecedenteFamiliarCorrectoTest()
    {
        //Accedemos la vista antecedentes familiares
        $response = $this->get('/paciente/999/antecedentes/familiares')->assertSee('Antecedentes familiares');
        //Realizamos la solicitud delete
        $response = $this->delete('/paciente/999/antecedentes/familiares/0');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/antecedentes/familiares');
        //Comprobamos que los datos del antecedente familiar se han eliminado correctamente
        $antecedenteFamiliar = Antecedentes_familiares::find(999);
        $enfermedadFamiliar = Enfermedades_familiar::find(999);
        $this->assertTrue(empty($antecedenteFamiliar));
        $this->assertTrue(empty($enfermedadFamiliar));
    }
}
