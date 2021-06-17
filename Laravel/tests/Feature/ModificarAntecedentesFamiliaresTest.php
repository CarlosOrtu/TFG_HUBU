<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Antecedentes_familiares;
use App\Models\Enfermedades_familiar;

class ModificarAntecedentesFamiliaresTest extends TestCase
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
        //crear el antecedente familiar a modificar
        $antecedenteFamiliarAModificar = new Antecedentes_familiares();
        $antecedenteFamiliarAModificar->id_paciente = 999;
        $antecedenteFamiliarAModificar->id_antecedente_f = 999;
        $antecedenteFamiliarAModificar->familiar = "Madre";
        $antecedenteFamiliarAModificar->save();
        //Crear la enfermedad del familiar a modificar
        $enfermedadFamiliarAModificar = new Enfermedades_familiar();
        $enfermedadFamiliarAModificar->id_antecedente_f = 999;
        $enfermedadFamiliarAModificar->id_enfermedad_f = 999;
        $enfermedadFamiliarAModificar->tipo = "Pulmón";
        $enfermedadFamiliarAModificar->save();
        //Realizamos el login con el administrador para poder acceder a todos las rutas de la web
        $this->get('/login')->assertSee('Login');
        $credentials = [
            "email" => "administrador@gmail.com",
            "password" => "1234",
        ];
        $this->post('/login', $credentials);
    }

    protected function tearDown(): void
    {
        //Eliminamos el usuario
        Pacientes::find(999)->delete();
        parent::tearDown();
    }

    /** @test */
    //Caso de prueba 1
    public function modificarAntecedenteFamiliarCorrectoTest()
    {
        //Accedemos la vista antecedentes familiares
        $response = $this->get('/paciente/999/antecedentes/familiares')->assertSee('Antecedentes familiares');
        $antecedenteFamiliar = [
            "familiar" => "Padre",
            "enfermedades" => ["Vejiga"],
        ];
        //Realizamos la solicitud put con los datos del antecedente familiares definidos anteriormente
        $response = $this->put('/paciente/999/antecedentes/familiares/0', $antecedenteFamiliar);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/antecedentes/familiares');
        //Comprobamos que los datos del antecedente familiar se han modificado correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Antecedentes_familiares[0]->familiar == "Padre");
        $this->assertTrue($paciente->Antecedentes_familiares[0]->Enfermedades_familiar[0]->tipo == "Vejiga");
    }

    /** @test */
    //Caso de prueba 2
    public function modificarAntecedenteFamiliarCampoFamiliarVacioTest()
    {
        //Accedemos la vista antecedentes familiares
        $response = $this->get('/paciente/999/antecedentes/familiares')->assertSee('Antecedentes familiares');
        $antecedenteFamiliar = [
            "familiar" => "",
            "enfermedades" => ["Vejiga"],
        ];
        //Realizamos la solicitud put con los datos del antecedente familiares definidos anteriormente
        $response = $this->put('/paciente/999/antecedentes/familiares/0', $antecedenteFamiliar);
        //Comprobamos que devuelve error en el campo familiar 
        $response->assertSessionHasErrors('familiar');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/antecedentes/familiares');
        //Comprobamos que los datos del antecedente familiar no se han modificado correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Antecedentes_familiares[0]->familiar == "Madre");
        $this->assertTrue($paciente->Antecedentes_familiares[0]->Enfermedades_familiar[0]->tipo == "Pulmón");
    }
}
