<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Antecedentes_oncologicos;

class ModificarAntecedentesOncologicosTest extends TestCase
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
        //Creamos el antecedente oncológico a modificar
        $antOModificar = new Antecedentes_oncologicos();
        $antOModificar->id_paciente = 999;
        $antOModificar->id_antecedente_o = 999;
        $antOModificar->tipo = "Próstata";
        $antOModificar->save();
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
    public function modificarAntecedenteOncologicoCorrectoTest()
    {
        //Accedemos la vista antecedentes oncológicos
        $response = $this->get('/paciente/999/antecedentes/oncologicos')->assertSee('Antecedentes oncológicos');
        $antecedenteO = [
            "tipo"=> "Hígado",
        ];
        //Realizamos la solicitud put con los datos del antecedente oncológicos definidos anteriormente
        $response = $this->put('/paciente/999/antecedentes/oncologicos/0', $antecedenteO);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/antecedentes/oncologicos');
        //Comprobamos que los datos del antecedente oncológico se han modificado correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Antecedentes_oncologicos[0]->tipo == "Hígado");
    }
}
